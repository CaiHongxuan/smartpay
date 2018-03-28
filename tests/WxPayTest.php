<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 14:15
 */

require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

//new \Hongxuan\Smartpay\PaymentServiceProvider();
//$payment = new \Hongxuan\Smart\PaymentManager();

//$payment->pay();
var_dump($this->app->make('payment'));
dd($this->app->payment);