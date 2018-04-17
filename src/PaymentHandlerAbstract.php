<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 18:05
 */

namespace Hongxuan\Smartpay;


abstract class PaymentHandlerAbstract implements PaymentInterface
{

    /**
     * @var array 配置信息
     */
    protected $config;

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

    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);
        if ($prefix == 'set') {
            array_set($this->config, snake_case(substr($name, 3)), array_get($arguments, '0', ''));
            return $this;
        }
        throw new PaymentException("Call to undefined method {$name}()");
    }
}