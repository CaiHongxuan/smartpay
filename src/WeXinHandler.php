<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:42
 */

namespace Hongxuan\Smartpay;


use InvalidArgumentException;

class WeXinHandler extends PaymentHandlerAbstract
{
    /**
     * 获取配置信息
     * @param $config
     * @return mixed
     */
    protected function getConfig($config)
    {
        if(!isset($config['app_id']) || !isset($config['app_key'])){
            throw new InvalidArgumentException("Configure app_id or app_key not found.");
        }

        return $config;
    }

    /**
     * 手机、电脑网站支付
     * @return mixed
     */
    function pay()
    {
        // TODO: Implement pay() method.
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