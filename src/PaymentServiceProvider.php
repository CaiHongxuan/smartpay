<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 12:33
 */

namespace Hongxuan\Smartpay;


use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true; // 延迟加载服务

    /**
     * Register the pay service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'payment');

        $this->registerPaymentManager();
    }

    /**
     * Register the payment manager instance.
     */
    protected function registerPaymentManager()
    {
        $this->app->singleton('payment', function ($app) {
            return new PaymentManager($app);
        });
    }

    /**
     * config path
     * @return string
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/payment.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['payment'];
    }

}