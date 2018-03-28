<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 18:05
 */

namespace Hongxuan\Smartpay;


abstract class PaymentHandlerAbstract
{

    /**
     * @var array 配置信息
     */
    protected $config;

    /**
     * @var array 传入的参数
     */
    protected $params;

    public function __construct(array $config = [])
    {
        $this->config = $this->getConfig($config);
    }

    /**
     * 获取配置信息
     * @param $config
     * @return mixed
     */
    abstract protected function getConfig($config);

    /**
     * 手机、电脑网站支付
     * @return mixed
     */
    abstract function pay();

    /**
     * 订单查询
     *
     * @return mixed
     */
    abstract function tradeQuery();

    /**
     * 订单退款
     *
     * @return mixed
     */
    abstract function refund();

    /**
     * 订单退款查询
     *
     * @return mixed
     */
    abstract function refundQuery();

    /**
     * 账单下载
     *
     * @return mixed
     */
    abstract function download();

}