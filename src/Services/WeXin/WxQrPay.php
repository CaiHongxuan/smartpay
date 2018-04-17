<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:18
 */

namespace Hongxuan\Smartpay\Services\WeXin;

use Hongxuan\Smartpay\PaymentException;
use Hongxuan\Smartpay\Utils\SomeUtils;
use Hongxuan\Smartpay\WeXinHandler;

/**
 * 微信扫码支付
 * Class WxQrPay
 * @package Hongxuan\Smartpay\Services\WeXin
 */
class WxQrPay extends WeXinHandler
{

    /**
     * 支付
     * @return array|false|mixed
     * @throws PaymentException
     */
    public function pay()
    {
        $this->setSign(self::TRADE_TYPE_WX_QR);
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        $result = $this->sendReq($xml, self::PAY_URL, 'POST');
        $result['out_trade_no'] = array_get($this->config, 'order.out_trade_no');
        $result['total_amount'] = array_get($this->config, 'order.total_amount');

//        // 扫码支付，返回链接
//        return $result['code_url'];
        return $result;

        /*
            [
                "return_code"  => "SUCCESS",
                "return_msg"   => "OK",
                "appid"        => "wx9c5a220e17f03ab1",
                "mch_id"       => "1486973282",
                "nonce_str"    => "Nr6wYqeNVkbrJEjU",
                "sign"         => "AA2DAD7F5AB823D048F2A6B9735F70F0",
                "result_code"  => "SUCCESS",
                "prepay_id"    => "wx15004032285761822bd055530424789348",
                "trade_type"   => "NATIVE",
                "code_url"     => "weixin://wxpay/bizpayurl?pr=hAYpr8S",
                "out_trade_no" => "0101",
                "total_amount" => 0.01
            ]
        */
    }

}