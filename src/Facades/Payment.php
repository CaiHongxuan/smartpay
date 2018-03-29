<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 17:19
 */

namespace Hongxuan\Smartpay\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 * @method static driver($driver = null)
 * @method static pay()
 * @method static tradeQuery()
 * @method static refund()
 * @method static refundQuery()
 * @method static download()
 * @package Hongxuan\Smartpay\Facades
 */
class Payment extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}