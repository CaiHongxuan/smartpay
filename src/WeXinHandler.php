<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:42
 */

namespace Hongxuan\Smartpay;


use GuzzleHttp\Client;
use Hongxuan\Smartpay\Services\WeXin\WxPubPay;
use Hongxuan\Smartpay\Services\WeXin\WxQrPay;
use Hongxuan\Smartpay\Services\WeXin\WxWapPay;
use Hongxuan\Smartpay\Utils\SomeUtils;

class WeXinHandler extends PaymentHandlerAbstract
{

    /**
     * @var array 配置信息
     */
    protected $config;

    protected $retData = [];

    /**
     * 支持的支付方式
     */
    const WX_QR = 'wx_qr'; // 扫码支付（用户打开扫一扫，扫码商户二维码完成支付）
    const WX_PUB = 'wx_pub'; // 公众号支付（用户在微信内进入商家的H5页面，页面内调用JSSDK完成支付）
    const WX_WAP = 'wx_wap'; // H5支付（用户在微信以外的浏览器请求微信支付的场景唤起微信支付）

    /**
     * 微信支付trade_type支付类型名称
     */
    // 扫码支付
    const TRADE_TYPE_WX_QR = 'NATIVE';
    // 公众号支付
    const TRADE_TYPE_WX_PUB = 'JSAPI';
    // H5支付
    const TRADE_TYPE_WX_WAP = 'MWEB';

    public static $pay_type = [
        self::WX_QR  => self::TRADE_TYPE_WX_QR,
        self::WX_PUB => self::TRADE_TYPE_WX_PUB,
//        self::WX_WAP => self::TRADE_TYPE_WX_WAP
    ];

    /**
     * 需要向微信请求的url
     * @var string $reqUrl
     */
    // 统一下单接口地址
    const PAY_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    // 查询订单接口地址
    const QUERY_URL = 'https://api.mch.weixin.qq.com/pay/orderquery';
    // 申请退款接口地址
    const REFUND_URL = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
    // 退款查询接口地址
    const REFUND_QUERY_URL = 'https://api.mch.weixin.qq.com/pay/refundquery';
    // 对账单下载接口地址
    const DOWNLOAD_URL = 'https://api.mch.weixin.qq.com/pay/downloadbill';
    // 关闭订单接口地址
    const CLOSE_URL = 'https://api.mch.weixin.qq.com/pay/closeorder';

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
        if (!isset($config['app_key']) || trim($config['app_key']) == "") {
            throw new PaymentException("app_key should not be NULL!");
        }
        if (!isset($config['mch_id']) || trim($config['mch_id']) == "") {
            throw new PaymentException("mch_id should not be NULL!");
        }
        if (!isset($config['private_key']) || trim($config['private_key']) == "") {
            throw new PaymentException("private_key should not be NULL!");
        }

        return $config;
    }

    /**
     * 手机、电脑网站支付
     * @return mixed
     * @throws PaymentException
     */
    function pay()
    {
        // 获取支付方式，默认为：wx_qr
        $pay_type = array_get($this->config, 'pay_type', self::WX_QR);
        if (!in_array($pay_type, array_keys(self::$pay_type))) {
            throw new PaymentException('Unsupported payment methods');
        }
        $order = array_get($this->config, 'order');
        // 检查订单号是否合法
        if (!isset($order['out_trade_no']) || empty($order['out_trade_no']) || mb_strlen($order['out_trade_no']) > 32) {
            throw new PaymentException('订单号不能为空，并且长度不能超过32位');
        }
        // 检查金额不能低于0.01
        if (!isset($order['total_amount']) || bccomp($order['total_amount'], 0.01, 2) === -1) {
            throw new PaymentException('支付金额不能低于 0.01 元');
        }
        // 检查 商品ID
        if (!isset($order['product_id']) || empty($order['product_id']) ) {
            throw new PaymentException('必须提供商品');
        }
        // 检查 商品名称
        if (!isset($order['body']) || empty($order['body'])) {
            throw new PaymentException('必须提供商品名称');
        }

        switch ($pay_type) {
            case self::WX_QR:
                return (new WxQrPay($this->config))->pay();
                break;
            case self::WX_PUB:
                return (new WxPubPay($this->config))->pay();
                break;
            case self::WX_WAP:
                return (new WxWapPay($this->config))->pay();
                break;
            default :
                return (new WxQrPay($this->config))->pay();
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
        $order = array_get($this->config, 'order');
        // 检查订单号是否合法
        if ((!isset($order['trade_no']) || empty($order['trade_no'])) && (!isset($order['out_trade_no']) || empty($order['out_trade_no']) || mb_strlen($order['out_trade_no']) > 32)) {
            throw new PaymentException('订单号不能为空，并且长度不能超过32位');
        }

        $this->setSign();
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        return $this->sendReq($xml, self::QUERY_URL, 'POST');

        /*
            [
                "return_code"      => "SUCCESS",
                "return_msg"       => "OK",
                "appid"            => "wx9c5a220e17f03ab1",
                "mch_id"           => "1486973282",
                "nonce_str"        => "fMv7cabjKj1PKf7Z",
                "sign"             => "FA006B0DB901118F6DD6E6A463658FBA",
                "result_code"      => "SUCCESS",
                "openid"           => "odWrUwmbRaYe-vMzPALsykRhM55g",
                "is_subscribe"     => "N",
                "trade_type"       => "JSAPI",
                "bank_type"        => "CFT",
                "total_fee"        => "1",
                "fee_type"         => "CNY",
                "transaction_id"   => "4200000017201711175229578785",
                "out_trade_no"     => "D1711170010",
                "attach"           => [],
                "time_end"         => "20171117141101",
                "trade_state"      => "SUCCESS",
                "cash_fee"         => "1",
                "trade_state_desc" => "支付成功"
            ]

            [
                "return_code"      => "SUCCESS",
                "return_msg"       => "OK",
                "appid"            => "wx9c5a220e17f03ab1",
                "mch_id"           => "1486973282",
                "nonce_str"        => "9MEtW1zymtseZrrj",
                "sign"             => "2F423F5FD65A140BE1B6B6D67B8A76F4",
                "result_code"      => "SUCCESS",
                "out_trade_no"     => "0101",
                "trade_state"      => "NOTPAY",
                "trade_state_desc" => "订单未支付"
            ]
        */
    }

    /**
     * 订单退款
     *
     * @return mixed
     * @throws PaymentException
     */
    function refund()
    {
        $order = array_get($this->config, 'order');
        // 检查订单号是否合法
        if ((!isset($order['trade_no']) || empty($order['trade_no'])) && (!isset($order['out_trade_no']) || empty($order['out_trade_no']) || mb_strlen($order['out_trade_no']) > 32)) {
            throw new PaymentException('订单号不能为空，并且长度不能超过32位');
        }
        // 检查订单总金额不能低于0.01
        if (!isset($order['total_amount']) || bccomp($order['total_amount'], 0.01, 2) === -1) {
            throw new PaymentException('订单总金额不能低于 0.01 元');
        }
        // 检查退款金额不能低于0.01
        if (!isset($order['refund_amount']) || bccomp($order['refund_amount'], 0.01, 2) === -1) {
            throw new PaymentException('退款金额不能低于 0.01 元');
        }
        // 检查商户系统内部的退款单号
        if (!isset($order['out_request_no']) || empty($order['out_request_no'])) {
            throw new PaymentException('退款单号不能为空');
        }

        array_set($this->config, 'order.op_user_id', array_get($order, 'op_user_id', array_get($this->config, 'mch_id')));
        $this->setSign();
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        return $this->sendReq($xml, self::REFUND_URL, 'POST');

        /*
            [
                "return_code"         => "SUCCESS",
                "return_msg"          => "OK",
                "appid"               => "wx9c5a220e17f03ab1",
                "mch_id"              => "1486973282",
                "nonce_str"           => "xa6gl6ii5xTU9dv6",
                "sign"                => "6DB0A23C45A8737B006C231D99208EA2",
                "result_code"         => "SUCCESS",
                "transaction_id"      => "4200000017201711175229578785",
                "out_trade_no"        => "D1711170010",
                "out_refund_no"       => "0101",
                "refund_id"           => "50000105022018041604166050205",
                "refund_channel"      => [],
                "refund_fee"          => "1",
                "coupon_refund_fee"   => "0",
                "total_fee"           => "1",
                "cash_fee"            => "1",
                "coupon_refund_count" => "0",
                "cash_refund_fee"     => "1"
            ]
        */
    }

    /**
     * 订单退款查询
     *
     * @return mixed
     * @throws PaymentException
     */
    function refundQuery()
    {
        $order = array_get($this->config, 'order');
        // 检查订单号是否合法
        if ((!isset($order['trade_no']) || empty($order['trade_no'])) && (!isset($order['out_trade_no']) || empty($order['out_trade_no']) || mb_strlen($order['out_trade_no']) > 32)) {
            throw new PaymentException('订单号不能为空，并且长度不能超过32位');
        }

        $this->setSign();
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        return $this->sendReq($xml, self::REFUND_QUERY_URL, 'POST');

        /*
            [
                "appid"                 => "wx9c5a220e17f03ab1",
                "cash_fee"              => "1",
                "mch_id"                => "1486973282",
                "nonce_str"             => "3M88vqkgq5fPpqRD",
                "out_refund_no_0"       => "0101",
                "out_trade_no"          => "D1711170010",
                "refund_account_0"      => "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_channel_0"      => "ORIGINAL",
                "refund_count"          => "1",
                "refund_fee"            => "1",
                "refund_fee_0"          => "1",
                "refund_id_0"           => "50000105022018041604166050205",
                "refund_recv_accout_0"  => "支付用户的零钱",
                "refund_status_0"       => "SUCCESS",
                "refund_success_time_0" => "2018-04-16 12:35:38",
                "result_code"           => "SUCCESS",
                "return_code"           => "SUCCESS",
                "return_msg"            => "OK",
                "sign"                  => "A14101149FE4ED53DF67400D41858C3D",
                "total_fee"             => "1",
                "transaction_id"        => "4200000017201711175229578785"
            ]
        */
    }

    /**
     * 账单下载
     *
     * @return mixed
     * @throws PaymentException
     */
    function download()
    {
        $order = array_get($this->config, 'order');
        // 检查对账单的日期
        if (!isset($order['bill_date']) || !preg_match('/^(\d+){8}$/', $order['bill_date'])) {
            throw new PaymentException('对账单的日期不能为空');
        }

        // 默认账单类型为：SUCCESS
        array_set($this->config, 'order.bill_type', array_get($order, 'bill_type', 'SUCCESS'));
        $this->setSign();
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        $client = new Client([
            'timeout' => '10.0'
        ]);

        $options = [
            'body'        => $xml,
            'verify'      => false,
            'http_errors' => false
        ];
        $response = $client->request('POST', self::DOWNLOAD_URL, $options);
        if ($response->getStatusCode() != '200') {
            throw new PaymentException('网络发生错误，请稍后再试curl返回码：' . $response->getReasonPhrase());
        }
        $body = $response->getBody()->getContents();
        // 格式化为数组
        $retData = SomeUtils::toArray($body);
        if ($retData && strtoupper($retData['return_code']) != 'SUCCESS') {
            throw new PaymentException('微信返回错误提示：' . $retData['return_msg']);
        }

        return $body;
//        $filename = storage_path('app/' .array_get($data, 'bill_date', date('Ymd'))) . '.txt';
//        File::put($filename, $body);
//
//        header('content-disposition:attachment;filename='.basename($filename));
//        header('content-length:'.filesize($filename));
//        readfile($filename);
//        exit;
    }

    /**
     * 关闭交易
     *
     * @return mixed
     * @throws PaymentException
     */
    function close()
    {
        $order = array_get($this->config, 'order');
        // 检查订单号是否合法
        if ((!isset($order['trade_no']) || empty($order['trade_no'])) && (!isset($order['out_trade_no']) || empty($order['out_trade_no']) || mb_strlen($order['out_trade_no']) > 32)) {
            throw new PaymentException('订单号不能为空，并且长度不能超过32位');
        }

        $this->setSign();
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        return $this->sendReq($xml, self::CLOSE_URL, 'POST');

        /*
            [
                "return_code" => "SUCCESS",
                "return_msg"  => "OK",
                "appid"       => "wx9c5a220e17f03ab1",
                "mch_id"      => "1486973282",
                "sub_mch_id"  => [],
                "nonce_str"   => "HnTndlLH6p5gXp7m",
                "sign"        => "9E53931CA162701FD16B5CC1E039FDE7",
                "result_code" => "SUCCESS"
            ]
        */
    }

    /**
     * 发送请求
     *
     * @param string $xml
     * @param string $reqUrl 请求地址
     * @param string $method 网络请求的方法， get post 等
     * @return array|false
     * @throws PaymentException
     */
    protected function sendReq($xml, $reqUrl, $method = 'POST')
    {
        if (is_null($reqUrl)) {
            throw new PaymentException('目前不支持该接口。请联系开发者添加');
        }
        $client = new Client([
            'timeout' => '10.0'
        ]);

        // 微信部分接口并不需要证书支持。这里为了统一，全部携带证书进行请求
        $pem_path = array_get($this->config, 'cert_path') ?: __DIR__ . DIRECTORY_SEPARATOR . 'cert';
        @mkdir($pem_path);
        $cert_path = $pem_path . DIRECTORY_SEPARATOR . 'apiclient_cert.pem';
        $key_path = $pem_path . DIRECTORY_SEPARATOR . 'apiclient_key.pem';
        $ca_path = $pem_path . DIRECTORY_SEPARATOR . 'rootca.pem';
        file_exists($cert_path) || file_put_contents($cert_path, SomeUtils::getRsaKeyValue(array_get($this->config, 'app_cert_pem'), 'public', 'CERT'));
        file_exists($key_path) || file_put_contents($key_path, SomeUtils::getRsaKeyValue(array_get($this->config, 'app_key_pem'), 'private', 'CERT'));
        $options = [
            'body'        => $xml,
            'cert'        => $cert_path,
            'ssl_key'     => $key_path,
            'verify'      => file_exists($ca_path) ? $ca_path : false,
            'http_errors' => false
        ];
        $response = $client->request($method, $reqUrl, $options);
        if ($response->getStatusCode() != '200') {
            throw new PaymentException('网络发生错误，请稍后再试curl返回码：' . $response->getReasonPhrase());
        }
        $body = $response->getBody()->getContents();
        // 格式化为数组
        $retData = SomeUtils::toArray($body);
        if (strtoupper($retData['return_code']) != 'SUCCESS') {
            throw new PaymentException('微信返回错误提示：' . $retData['return_msg']);
        }
        if (strtoupper($retData['result_code']) != 'SUCCESS') {
            $msg = $retData['err_code_des'] ? $retData['err_code_des'] : $retData['err_msg'];
            throw new PaymentException('微信返回错误提示：' . $msg);
        }
        // 检查返回的数据是否被篡改
        $flag = $this->verifySign($retData);
        if (!$flag) {
            throw new PaymentException('微信返回数据被篡改。请检查网络是否安全！');
        }

        return $retData;
    }

    /**
     * 设置签名
     *
     * @param string $trade_type [微信支付类型]
     * @throws PaymentException
     */
    protected function setSign($trade_type = '')
    {
        $this->buildData($trade_type);
        $data = $this->retData;

        unset($data['sign']);

        ksort($data);
        reset($data);

        $signStr = SomeUtils::createLinkString($data);
        $this->retData['sign'] = $this->makeSign($signStr);
    }

    /**
     * 构建 公共参数
     *
     * @property string $app_id 微信分配的公众账号ID
     * @property string $mch_id 微信支付分配的商户号
     * @property string time_stamp 设置支付时间戳
     * @property string $notify_url 异步通知的url
     * @property string $trade_type 支付类型
     * @param string    $trade_type 支付类型
     */
    protected function buildData($trade_type = '')
    {
        $bizContent = $this->buildBiz($trade_type);

        $signData = [
            'appid'     => trim(array_get($this->config, 'app_id')),
            'mch_id'    => trim(array_get($this->config, 'mch_id')),
            'sign_type' => array_get($this->config, 'sign_type'), // 签名方式
            'nonce_str' => SomeUtils::getNonceStr(), // 设置随机字符串，不长于32位。推荐随机数生成算法
        ];

        if (in_array($trade_type, self::$pay_type)) {
            $signData['notify_url'] = array_get($this->config, 'notify_url'); // 异步通知的url
            $signData['time_stamp'] = time(); // 支付时间戳
            $signData['spbill_create_ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; // 设置APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP
        }

        $signData = array_merge($signData, $bizContent);

        // 移除数组中的空值
        $this->retData = SomeUtils::paraFilter($signData);
    }

    /**
     * 业务请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递
     *
     * @param string $trade_type [支付类型]
     * @return array
     */
    protected function buildBiz($trade_type = '')
    {
        $order = array_get($this->config, 'order');
        $content = [];

        // 设置支付类型
        !empty($trade_type) && $content['trade_type'] = $trade_type;
        // 微信交易号
        array_key_exists('trade_no', $order) && $content['transaction_id'] = strval(array_get($order, 'trade_no'));
        // 商户订单号，设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
        array_key_exists('out_trade_no', $order) && $content['out_trade_no'] = strval(array_get($order, 'out_trade_no'));
        // 判断订单总金额，单位为分，只能为整数，详见支付金额是否存在
        array_key_exists('total_amount', $order) && $content['total_fee'] = strval(bcmul(array_get($order, 'total_amount'), 100, 0));
        // 设置商品或支付单简要描述
        array_key_exists('body', $order) && $content['body'] = strval(array_get($order, 'body'));
        // 设置商品名称明细列表
        array_key_exists('detail', $order) && $content['detail'] = json_encode(strval(array_get($order, 'detail')), JSON_UNESCAPED_UNICODE);
        // 设置trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
        array_key_exists('product_id', $order) && $content['product_id'] = strval(array_get($order, 'product_id'));
        // 获取附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
        array_key_exists('attach', $order) && $content['attach'] = strval(array_get($order, 'attach'));
        // 设置订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则
        array_key_exists('time_start', $order) && $content['time_start'] = array_get($order, 'time_start');
        // 设置订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则
        array_key_exists('time_expire', $order) && $content['time_expire'] = array_get($order, 'time_expire');
        // 设置商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
        array_key_exists('goods_tag', $order) && $content['goods_tag'] = strval(array_get($order, 'goods_tag'));
        // 获取trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 的值
        array_key_exists('openid', $order) && $content['openid'] = strval(array_get($order, 'openid'));

        // 设置微信支付分配的终端设备号，与下单一致
        array_key_exists('device_info', $order) && $content['device_info'] = strval(array_get($order, 'device_info'));
        // 设置商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
        array_key_exists('out_request_no', $order) && $content['out_refund_no'] = strval(array_get($order, 'out_request_no'));
        // 设置退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
        array_key_exists('refund_amount', $order) && $content['refund_fee'] = strval(bcmul(array_get($order, 'refund_amount'), 100, 0));
        // 设置微信退款单号refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：refund_id>out_refund_no>transaction_id>out_trade_no
        array_key_exists('refund_id', $order) && $content['refund_id'] = strval(array_get($order, 'refund_id'));
        // 退款原因
        array_key_exists('return_reason', $order) && $content['refund_desc'] = strval(array_get($order, 'return_reason'));
        // 设置操作员帐号, 默认为商户号
        array_key_exists('op_user_id', $order) && $content['op_user_id'] = strval(array_get($order, 'op_user_id', array_get($this->config, 'mch_id')));

        // 设置下载对账单的日期，格式：yyyyMMdd，如：20140603
        array_key_exists('bill_date', $order) && $content['bill_date'] = strval(array_get($order, 'bill_date'));
        // 设置ALL，返回当日所有订单信息；默认值SUCCESS，返回当日成功支付的订单；REFUND，返回当日退款订单；REVOKED，已撤销的订单
        array_key_exists('bill_type', $order) && $content['bill_type'] = strtoupper(array_get($order, 'bill_type'));

        // 获取订单详情扩展字符串的值
        array_key_exists('package', $order) && $content['package'] = strval(array_get($order, 'package'));
        // 设置签名方式
        array_key_exists('pay_sign', $order) && $content['paySign'] = strval(array_get($order, 'pay_sign'));

        // 获取扫码支付授权码，设备读取用户微信中的条码或者二维码信息的值
        array_key_exists('auth_code', $order) && $content['auth_code'] = strval(array_get($this->config, 'auth_code'));

        // 设置货币类型，设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
        array_key_exists('fee_type', $order) && $content['fee_type'] = strval(array_get($this->config, 'fee_type'));
        // 设置货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
        array_key_exists('refund_fee_type', $order) && $content['refund_fee_type'] = strval(array_get($this->config, 'refund_fee_type', array_get($this->config, 'fee_type')));

        return $content;
    }

    /**
     * 签名算法实现
     * @param string $signStr
     * @return string
     */
    protected function makeSign($signStr)
    {
        switch (array_get($this->config, 'sign_type')) {
            case 'MD5':
                $signStr .= '&key=' . array_get($this->config, 'private_key');
                $sign = md5($signStr);
                break;
            default:
                $sign = '';
        }
        return strtoupper($sign);
    }

    /**
     * 检查微信返回的数据是否被篡改过
     *
     * @param array $retData
     * @return bool
     * @throws PaymentException
     */
    protected function verifySign(array $retData)
    {
        $retSign = $retData['sign'];
        unset($retData['sign'], $retData['sign_type']);
        $values = SomeUtils::paraFilter($retData);

        ksort($values);
        reset($values);

        $signStr = SomeUtils::createLinkString($values);

        $sign = $this->makeSign($signStr);

        return $sign === $retSign;
    }

}