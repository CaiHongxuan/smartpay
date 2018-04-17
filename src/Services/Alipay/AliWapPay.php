<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:09
 */

namespace Hongxuan\Smartpay\Services\Alipay;

use Hongxuan\Smartpay\PaymentException;
use Hongxuan\Smartpay\AlipayHandler;
use Hongxuan\Smartpay\Utils\SomeUtils;

/**
 * 支付宝手机网站支付
 * Class AliWapPay
 * @package Hongxuan\Smartpay\Services\Alipay
 */
class AliWapPay extends AlipayHandler
{

    /**
     * 支付
     * @return string
     * @throws PaymentException
     */
    public function pay()
    {
        $this->setSign(self::API_METHOD_NAME_WAP_PAY);
        $data = $this->retData;

        $sign = $data['sign'];
        unset($data['sign']);
        ksort($data);
        reset($data);

        // 支付宝新版本 需要转码
        foreach ($data as &$value) {
            $value = SomeUtils::transcode($value, array_get($this->config, 'charset'));
        }
        $data['sign'] = $sign; // sign 需要放在末尾

        header('Location: ' . array_get($this->config, 'gatewayUrl') . '?' . http_build_query($data));
        exit;
    }

}