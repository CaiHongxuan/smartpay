<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 10:02
 */

namespace Hongxuan\Smartpay;


class PaymentException extends \Exception
{

    /**
     * 获取错误信息
     * @return string
     */
    public function errorMsg()
    {
        return $this->getMessage();
    }

}