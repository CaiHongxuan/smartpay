<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 17:19
 */

namespace Hongxuan\Smartpay\Facades;


use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}