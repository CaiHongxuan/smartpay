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
 * @method static close()
 * @method static setPayType(string $pay_type)
 * @method static setOrder($order = [])
 * @method static setNotifyUrl(string $url)
 * @method static setReturnUrl(string $url)
 * @package Hongxuan\Smartpay\Facades
 */
class Payment extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}