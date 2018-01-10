<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 17:58
 */

namespace Hongxuan\Smart;


class Payment
{
    /**
     * The payment handler implementation.
     *
     * @var PaymentHandlerInterface
     */
    protected $handler;

    /**
     * Payment constructor.
     * @param PaymentHandlerInterface $handler
     */
    public function __construct(PaymentHandlerInterface $handler)
    {
        $this->handler = $handler;
    }
}