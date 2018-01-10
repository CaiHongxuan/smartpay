<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 12:33
 */

namespace Hongxuan\Smart;


use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register the pay service provider.
     */
    public function register()
    {
        $this->registerPaymentManager();

        $this->registerPaymentDriver();
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
     * Register the payment driver instance.
     */
    protected function registerPaymentDriver()
    {
        $this->app->singleton('payment.store', function ($app) {
            $manager = $app['payment'];

            return $manager->driver();
        });
    }

}