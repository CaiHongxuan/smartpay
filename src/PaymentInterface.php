<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 10:41
 */

namespace Hongxuan\Smartpay;


interface PaymentInterface
{

    /**
     * 手机、电脑网站支付
     * @return mixed
     */
    function pay();

    /**
     * 订单查询
     *
     * @return mixed
     */
    function tradeQuery();

    /**
     * 订单退款
     *
     * @return mixed
     */
    function refund();

    /**
     * 订单退款查询
     *
     * @return mixed
     */
    function refundQuery();

    /**
     * 账单下载
     *
     * @return mixed
     */
    function download();
}