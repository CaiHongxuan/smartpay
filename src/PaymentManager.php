<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:41
 */

namespace Hongxuan\Smartpay;


use Illuminate\Support\Arr;
use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    /**
     * Get the payment configuration.
     *
     * @return mixed
     */
    public function getPaymentConfig()
    {
        return $this->app['config']['payment'];
    }

    /**
     * Get the default payment driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['payment.default'];
    }

    /**
     * set the default payment driver name.
     *
     * @param $name
     * @return mixed
     */
    public function setDefaultDriver($name)
    {
        return $this->app['config']['payment.default'] = $name;
    }

    /**
     * Create an instance of the alipay payment driver.
     *
     * @return mixed
     */
    protected function createAlipayDriver()
    {
        return $this->buildPayment(AlipayHandler::class, Arr::get($this->getPaymentConfig(), 'drivers.alipay'));
    }

    /**
     * Create an instance of the weixin payment driver.
     *
     * @return mixed
     */
    protected function createWeixinDriver()
    {
        return $this->buildPayment(WeXinHandler::class, Arr::get($this->getPaymentConfig(), 'drivers.weixin'));
    }

    /**
     * Build the payment instance.
     *
     * @param $handler
     * @return mixed
     */
    public function buildPayment($handler, $config)
    {
        return new $handler($config);
    }
}