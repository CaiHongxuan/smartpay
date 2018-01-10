<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 18:05
 */

namespace Hongxuan\Smart;


interface PaymentHandlerInterface
{

    /**
     * 手机、电脑网站支付
     * @return mixed
     */
    public function pay();

    /**
     * 订单查询
     *
     * @return mixed
     */
    public function tradeQuery();

    /**
     * 订单退款
     *
     * @return mixed
     */
    public function refund();

    /**
     * 订单退款查询
     *
     * @return mixed
     */
    public function refundQuery();

    /**
     * 账单下载
     *
     * @return mixed
     */
    public function download();

}