<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 15:59
 */

namespace Hongxuan\Smartpay\Utils;


use Hongxuan\Smartpay\PaymentException;

class RsaEncrypt
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * 设置key
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * RSA签名, 此处秘钥是私有秘钥
     * @param string $data 签名的数组
     * @return string
     * @throws PaymentException
     */
    public function encrypt($data)
    {
        if ($this->key === false) {
            return '';
        }
        $res = openssl_get_privatekey($this->key);
        if (empty($res)) {
            throw new PaymentException('您使用的私钥格式错误，请检查RSA私钥配置');
        }
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA解密 此处秘钥是用户私有秘钥
     * @param string $content 需要解密的内容，密文
     * @return string
     * @throws PaymentException
     */
    public function decrypt($content)
    {
        if ($this->key === false) {
            return '';
        }
        $res = openssl_get_privatekey($this->key);
        if (empty($res)) {
            throw new PaymentException('您使用的私钥格式错误，请检查RSA私钥配置');
        }
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result  = '';
        for ($i = 0; $i < strlen($content)/128; $i++) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA验签 ，此处的秘钥，是第三方公钥
     * @param string $data 待签名数据
     * @param string $sign 要校对的的签名结果
     * @throws PaymentException
     * @return bool
     */
    public function rsaVerify($data, $sign)
    {
        // 初始时，使用公钥key
        $res = openssl_get_publickey($this->key);
        if (empty($res)) {
            throw new PaymentException('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        }
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }
}