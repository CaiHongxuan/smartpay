<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:42
 */

namespace Hongxuan\Smartpay;


use Exception;

class AlipayHandler extends PaymentHandlerAbstract
{

    /**
     * 获取配置信息
     * @param $config
     * @return mixed
     * @throws Exception
     */
    protected function getConfig($config)
    {
        if(!isset($config['app_id'])||trim($config['app_id'])==""){
            throw new Exception("appid should not be NULL!");
        }
        if(!isset($config['mpk'])||trim($config['mpk'])==""){
            throw new Exception("private_key should not be NULL!");
        }
        if(!isset($config['alipay_public_key'])||trim($config['alipay_public_key'])==""){
            throw new Exception("alipay_public_key should not be NULL!");
        }
        if(!isset($config['charset'])||trim($config['charset'])==""){
            throw new Exception("charset should not be NULL!");
        }
        if(!isset($config['sign_type'])||trim($config['sign_type'])==""){
            throw new Exception("sign_type should not be NULL!");
        }
        if(!isset($config['gatewayUrl'])||trim($config['gatewayUrl'])==""){
            throw new Exception("gateway_url should not be NULL!");
        }

        return $config;
    }

    /**
     * 手机、电脑网站支付
     * @return mixed
     */
    function pay()
    {
//        dd(array_get($this->config, 'order'));
        dd($this->config);

//        //设置订单信息
//        $payData = [
//            'product_code' => 'FAST_INSTANT_TRADE_PAY',
//            'out_trade_no' => '1',
//            'subject' => 'subject',
////            'total_amount' => 'total',
//            'total_amount' => 0.01,
//            'body' => 'body',
//        ];
//        $bizContent = json_encode($payData, JSON_UNESCAPED_UNICODE);
////        dd(dirname(__FILE__) . '/alipay.trade.page.pay-PHP-UTF-8/aop/AopClient.php');
//
//        require_once dirname(__FILE__) . '/alipay.trade.page.pay-PHP-UTF-8/aop/AopClient.php';
//        require_once dirname(__FILE__) . '/alipay.trade.page.pay-PHP-UTF-8/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
//
//        //构造参数
//        $aop = new AopClient();
//        $aop->gatewayUrl = array_get($this->config, 'alipay_gateway_new');
//        $aop->appId = array_get($this->config, 'app_id');
//        $aop->rsaPrivateKey = array_get($this->config, 'app_private_key');
//        $aop->apiVersion = array_get($this->config, 'version', '1.0');
//        $aop->signType = array_get($this->config, 'sign_type');
//        $aop->postCharset = array_get($this->config, 'charset');
//        $aop->format = array_get($this->config, 'format', 'json');
//        $request = new AlipayTradePagePayRequest();
//        $request->setReturnUrl(array_get($this->config, 'return'));
//        $request->setNotifyUrl(array_get($this->config, 'notify'));
//        $request->setBizContent($bizContent);
//
//        //请求
//        $result = $aop->pageExecute($request);
//        //输出
//        echo $result;
    }

    /**
     * 订单查询
     *
     * @return mixed
     */
    function tradeQuery()
    {
        // TODO: Implement tradeQuery() method.
    }

    /**
     * 订单退款
     *
     * @return mixed
     */
    function refund()
    {
        // TODO: Implement refund() method.
    }

    /**
     * 订单退款查询
     *
     * @return mixed
     */
    function refundQuery()
    {
        // TODO: Implement refundQuery() method.
    }

    /**
     * 账单下载
     *
     * @return mixed
     */
    function download()
    {
        // TODO: Implement download() method.
    }
}