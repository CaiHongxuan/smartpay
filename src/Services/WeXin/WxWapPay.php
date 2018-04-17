<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:16
 */

namespace Hongxuan\Smartpay\Services\WeXin;

use Hongxuan\Smartpay\PaymentException;
use Hongxuan\Smartpay\WeXinHandler;

/**
 * 微信H5支付
 * Class WxWapPay
 * @package Hongxuan\Smartpay\Services\WeXin
 */
class WxWapPay extends WeXinHandler
{

    /**
     * 支付
     * @return array|false|mixed
     * @throws PaymentException
     */
    public function pay()
    {
        throw new PaymentException('暂不支持该支付方式');
    }

}