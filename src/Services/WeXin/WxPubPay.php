<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:16
 */

namespace Hongxuan\Smartpay\Services\WeXin;

use Hongxuan\Smartpay\PaymentException;
use Hongxuan\Smartpay\Utils\SomeUtils;
use Hongxuan\Smartpay\WeXinHandler;

/**
 * 微信公众号支付
 * Class WxPubPay
 * @package Hongxuan\Smartpay\Services\WeXin
 */
class WxPubPay extends WeXinHandler
{

    /**
     * 支付
     * @return mixed|string
     * @throws PaymentException
     */
    public function pay()
    {
        // 公众号支付,必须设置openid
        if (!array_get($this->config, 'order.openid')) {
            throw new PaymentException('用户在商户appid下的唯一标识,公众号支付,必须设置该参数.');
        }

        $this->setSign(self::TRADE_TYPE_WX_PUB);
        $data = $this->retData;
        $xml = SomeUtils::toXml($data);

        $ret = $this->sendReq($xml, self::PAY_URL, 'POST');

        $result = [
            'appId'     => $ret['appid'],
            'timeStamp' => time(),
            'nonceStr'  => SomeUtils::getNonceStr(),
            'package'   => 'prepay_id=' . $ret['prepay_id'],
            'signType'  => array_get($this->config, 'sign_type')
        ];
        $signStr = SomeUtils::createLinkString($result);
        $result['paySign'] = $this->makeSign($signStr);

        return $result;

        /*
            [
                "appId"     => "wx9c5a220e17f03ab1",
                "timeStamp" => 1523723965,
                "nonceStr"  => "vtshi2urrghbh5cvxzhemrgqrov0a2ge",
                "package"   => "prepay_id=wx15003925634430d07f6250764238496132",
                "signType"  => "MD5",
                "paySign"   => "5A9AB71E86423752C03D30793FDEFF12"
            ]
         */
    }

}