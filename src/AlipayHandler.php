<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:42
 */

namespace Hongxuan\Smartpay;


use GuzzleHttp\Client;
use Hongxuan\Smartpay\Services\Alipay\AliWapPay;
use Hongxuan\Smartpay\Services\Alipay\AliWebPay;
use Hongxuan\Smartpay\Utils\Rsa2Encrypt;
use Hongxuan\Smartpay\Utils\RsaEncrypt;
use Hongxuan\Smartpay\Utils\SomeUtils;
use InvalidArgumentException;

class AlipayHandler extends PaymentHandlerAbstract
{

    /**
     * @var array 配置信息
     */
    protected $config;

    protected $retData = [];

    /**
     * 支持的支付方式
     */
    const ALI_WEB = 'ali_web'; // 支付宝电脑网站支付
    const ALI_WAP = 'ali_wap'; // 支付宝手机网站支付

    public static $pay_type = [
        self::ALI_WEB,
        self::ALI_WAP
    ];

    /**
     * 支付宝API接口名称
     */
    // web 支付接口名称
    const API_METHOD_NAME_WEB_PAY = 'alipay.trade.page.pay';
    // wap 支付接口名称
    const API_METHOD_NAME_WAP_PAY = 'alipay.trade.wap.pay';
    // 交易查询接口名称
    const API_METHOD_NAME_QUERY = 'alipay.trade.query';
    // 交易退款接口名称
    const API_METHOD_NAME_REFUND = 'alipay.trade.refund';
    // 交易退款查询接口名称
    const API_METHOD_NAME_REFUND_QUERY = 'alipay.trade.fastpay.refund.query';
    // 交易关闭接口名称
    const API_METHOD_NAME_CLOSE = 'alipay.trade.close';
    // 对账单下载接口名称
    const API_METHOD_NAME_DOWNLOAD = 'alipay.data.dataservice.bill.downloadurl.query';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->config = $config;
    }

    /**
     * 获取配置信息
     * @param $config
     * @return mixed
     * @throws PaymentException
     */
    protected function getConfig($config)
    {
        if (!isset($config['app_id']) || trim($config['app_id']) == "") {
            throw new PaymentException("app_id should not be NULL!");
        }
        if (!isset($config['private_key']) || trim($config['private_key']) == "") {
            throw new PaymentException("private_key should not be NULL!");
        }
        if (!isset($config['alipay_public_key']) || trim($config['alipay_public_key']) == "") {
            throw new PaymentException("alipay_public_key should not be NULL!");
        }
        if (!isset($config['charset']) || trim($config['charset']) == "") {
            throw new PaymentException("charset should not be NULL!");
        }
        if (!isset($config['sign_type']) || trim($config['sign_type']) == "") {
            throw new PaymentException("sign_type should not be NULL!");
        }
        if (!isset($config['gatewayUrl']) || trim($config['gatewayUrl']) == "") {
            throw new PaymentException("gateway_url should not be NULL!");
        }

        return $config;
    }

    /**
     * 手机、电脑网站支付
     * @throws PaymentException
     */
    function pay()
    {
        // 获取支付方式，默认为：ali_web
        $pay_type = array_get($this->config, 'pay_type', self::ALI_WEB);
        if (!in_array($pay_type, self::$pay_type)) {
            throw new PaymentException('Unsupported payment methods');
        }

        switch ($pay_type) {
            case self::ALI_WEB:
                (new AliWebPay($this->config))->pay();
                break;
            case self::ALI_WAP:
                (new AliWapPay($this->config))->pay();
                break;
            default :
                (new AliWebPay($this->config))->pay();
        }
    }

    /**
     * 订单查询
     *
     * @return mixed
     * @throws PaymentException
     */
    function tradeQuery()
    {
        $this->setSign(self::API_METHOD_NAME_QUERY);
        $data = $this->retData;

        return $this->sendReq($data, self::API_METHOD_NAME_QUERY, 'GET');
    }

    /**
     * 订单退款
     *
     * @return mixed
     * @throws PaymentException
     */
    function refund()
    {
        $this->setSign(self::API_METHOD_NAME_REFUND);
        $data = $this->retData;

        return $this->sendReq($data, self::API_METHOD_NAME_REFUND, 'GET');
    }

    /**
     * 订单退款查询
     *
     * @return mixed
     * @throws PaymentException
     */
    function refundQuery()
    {
        $this->setSign(self::API_METHOD_NAME_REFUND_QUERY);
        $data = $this->retData;

        return $this->sendReq($data, self::API_METHOD_NAME_REFUND_QUERY, 'GET');
    }

    /**
     * 对账单下载
     *
     * @return mixed
     * @throws PaymentException
     */
    function download()
    {
        $this->setSign(self::API_METHOD_NAME_DOWNLOAD);
        $data = $this->retData;

        $result = $this->sendReq($data, self::API_METHOD_NAME_DOWNLOAD, 'GET');

        if (array_get($result, 'code') == 10000) { // 请求成功
            header('Location: ' . array_get($result, 'bill_download_url'));
            exit;
        }

        return $result;
    }

    /**
     * 关闭交易
     *
     * @return mixed
     * @throws PaymentException
     */
    function close()
    {
        $this->setSign(self::API_METHOD_NAME_CLOSE);
        $data = $this->retData;

        return $this->sendReq($data, self::API_METHOD_NAME_CLOSE, 'GET');
    }


    /**
     * 支付宝业务发送网络请求，并验证签名
     * @param array $data
     * @param string $method_name 支付宝API接口名称
     * @param string $method 网络请求的方法， get post 等
     * @return mixed
     * @throws PaymentException
     */
    protected function sendReq(array $data, $method_name = '', $method = 'GET')
    {
        $client = new Client([
            'base_uri' => array_get($this->config, 'gatewayUrl'),
            'timeout'  => '10.0'
        ]);
        $method = strtoupper($method);
        $options = [];
        if ($method === 'GET') {
            $options = [
                'verify'      => false,
                'query'       => $data,
                'http_errors' => false
            ];
        } elseif ($method === 'POST') {
            $options = [
                'verify'      => false,
                'form_params' => $data,
                'http_errors' => false
            ];
        }
        // 发起网络请求
        $response = $client->request($method, '', $options);
        if ($response->getStatusCode() != '200') {
            throw new PaymentException('网络发生错误，请稍后再试curl返回码：' . $response->getReasonPhrase());
        }
        $body = $response->getBody()->getContents();
        try {
            $body = \GuzzleHttp\json_decode($body, true);
        } catch (InvalidArgumentException $e) {
            throw new PaymentException('返回数据 json 解析失败');
        }
        $responseKey = str_ireplace('.', '_', $method_name) . '_response';
        if (!isset($body[$responseKey])) {
            throw new PaymentException('支付宝系统故障或非法请求');
        }
        // 验证签名，检查支付宝返回的数据
        $flag = $this->verifySign($body[$responseKey], $body['sign']);
        if (!$flag) {
            throw new PaymentException('支付宝返回数据被篡改。请检查网络是否安全！');
        }

        return $body[$responseKey];
    }

    /**
     * 设置签名
     * @param $method_name [支付宝API接口名称]
     * @throws PaymentException
     */
    protected function setSign($method_name)
    {
        $this->buildData($method_name);
        $data = $this->retData;

        unset($data['sign']);

        ksort($data);
        reset($data);

        $signStr = SomeUtils::createLinkString($data);
        $this->retData['sign'] = $this->makeSign($signStr);
    }

    /**
     * 构建 支付 加密数据
     * @param $method_name [支付宝API接口名称]
     */
    protected function buildData($method_name)
    {
        $bizContent = $this->getBizContent();
        $bizContent = SomeUtils::paraFilter($bizContent);// 过滤掉空值
        $signData = [
            // 公共参数
            'app_id'      => array_get($this->config, 'app_id'),
            'method'      => $method_name,
            'format'      => array_get($this->config, 'format'),
            'charset'     => array_get($this->config, 'charset'),
            'sign_type'   => array_get($this->config, 'sign_type'),
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'     => array_get($this->config, 'version'),
            'notify_url'  => array_get($this->config, 'notify_url'),
            // 业务参数
            'biz_content' => json_encode($bizContent, JSON_UNESCAPED_UNICODE),
        ];
        // 电脑支付、wap支付 添加额外参数
        if (in_array($method_name, [self::API_METHOD_NAME_WEB_PAY, self::API_METHOD_NAME_WAP_PAY])) {
            $signData['return_url'] = array_get($this->config, 'return_url');
        }
        // 移除数组中的空值
        $this->retData = SomeUtils::paraFilter($signData);
    }

    /**
     * 业务请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递
     *
     * @return array
     *
     * 返回数据格式如下：
     *     $content = [
     *         // 支付宝交易号
     *         'trade_no'       => strval(array_get($order, 'trade_no')),
     *         // 商户订单号
     *         'out_trade_no'   => strval(array_get($order, 'out_trade_no')),
     *         // 为固定值产品标示码，固定值：QUICK_WAP_PAY
     *         'product_code'   => 'FAST_INSTANT_TRADE_PAY',
     *         // 支付金额
     *         'total_amount'   => strval(array_get($order, 'total_amount')),
     *         // 订单名称
     *         'subject'        => strval(array_get($order, 'subject')),
     *         // 商品描述
     *         'body'           => strval(array_get($order, 'body')),
     *         // 超时时间
     *         'time_express'   => strval(array_get($order, 'time_express')),
     *         // 退款金额
     *         'refund_amount'  => strval(array_get($order, 'refund_amount')),
     *         // 退款请求号，标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传，如果在退款请求时未传入，则该值为创建交易时的外部交易号，退款查询接口必填
     *         'out_request_no' => strval(array_get($order, 'out_request_no')),
     *         // 退款原因
     *         'return_reason'  => strval(array_get($order, 'return_reason')),
     *         // 账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；
     *         // trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
     *         'bill_type'      => strval(array_get($order, 'bill_type', 'signcustomer')),
     *         // 账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
     *         'bill_date'      => strval(array_get($order, 'bill_date')),
     *     ];
     *
     */
    protected function getBizContent()
    {
        $order = array_get($this->config, 'order');
        $content = [];

        // 支付宝交易号
        array_key_exists('trade_no', $order) && $content['trade_no'] = strval(array_get($order, 'trade_no'));
        // 商户订单号
        array_key_exists('out_trade_no', $order) && $content['out_trade_no'] = strval(array_get($order, 'out_trade_no'));
        // 为固定值产品标示码，固定值：QUICK_WAP_PAY
        $content['product_code'] = 'FAST_INSTANT_TRADE_PAY';
        // 支付金额
        array_key_exists('total_amount', $order) && $content['total_amount'] = strval(array_get($order, 'total_amount'));
        // 订单名称
        array_key_exists('subject', $order) && $content['subject'] = strval(array_get($order, 'subject'));
        // 商品描述
        array_key_exists('body', $order) && $content['body'] = strval(array_get($order, 'body'));
        // 超时时间
        array_key_exists('time_express', $order) && $content['time_express'] = strval(array_get($order, 'time_express'));
        // 退款金额
        array_key_exists('refund_amount', $order) && $content['refund_amount'] = strval(array_get($order, 'refund_amount'));
        // 退款请求号，标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传，如果在退款请求时未传入，则该值为创建交易时的外部交易号，退款查询接口必填
        array_key_exists('out_request_no', $order) && $content['out_request_no'] = strval(array_get($order, 'out_request_no'));
        // 退款原因
        array_key_exists('return_reason', $order) && $content['return_reason'] = strval(array_get($order, 'return_reason'));
        // 账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；
        // trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
        array_key_exists('bill_type', $order) && $content['bill_type'] = strtolower(array_get($order, 'bill_type'));
        // 账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
        array_key_exists('bill_date', $order) && $content['bill_date'] = strval(array_get($order, 'bill_date'));

        return $content;
    }

    /**
     * 签名算法实现
     * @param string $signStr
     * @return string
     * @throws PaymentException
     */
    protected function makeSign($signStr)
    {
        switch (array_get($this->config, 'sign_type')) {
            case 'RSA':
                $rsa = new RsaEncrypt(SomeUtils::getRsaKeyValue(array_get($this->config, 'private_key')));
                $sign = $rsa->encrypt($signStr);
                break;
            case 'RSA2':
                $rsa = new Rsa2Encrypt(SomeUtils::getRsaKeyValue(array_get($this->config, 'private_key')));
                $sign = $rsa->encrypt($signStr);
                break;
            default:
                $sign = '';
        }
        return $sign;
    }

    /**
     * 检查支付宝数据 签名是否被篡改
     * @param array  $data
     * @param string $sign 支付宝返回的签名结果
     * @return bool
     * @throws PaymentException
     */
    protected function verifySign(array $data, $sign)
    {
        $preStr = \GuzzleHttp\json_encode($data, JSON_UNESCAPED_UNICODE);// 主要是为了解决中文问题
        if (array_get($this->config, 'sign_type') === 'RSA') {// 使用RSA
            $rsa = new RsaEncrypt(SomeUtils::getRsaKeyValue(array_get($this->config, 'alipay_public_key'), 'public'));
            return $rsa->rsaVerify($preStr, $sign);
        } elseif (array_get($this->config, 'sign_type') === 'RSA2') {// 使用rsa2方式
            $rsa = new Rsa2Encrypt(SomeUtils::getRsaKeyValue(array_get($this->config, 'alipay_public_key'), 'public'));
            return $rsa->rsaVerify($preStr, $sign);
        } else {
            return false;
        }
    }
}