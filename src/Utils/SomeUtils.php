<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:38
 */

namespace Hongxuan\Smartpay\Utils;


use Hongxuan\Smartpay\PaymentException;

class SomeUtils
{
    /**
     * 输出xml字符
     * @param array $values
     * @return string|bool
     **/
    public static function toXml($values)
    {
        if (!is_array($values) || count($values) <= 0) {
            return false;
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            } else {
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @return array|false
     */
    public static function toArray($xml)
    {
        if (!$xml) {
            return false;
        }
        // 检查xml是否合法
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $xml, true)) {
            xml_parser_free($xml_parser);
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
     * @param string $text 二维码包含的信息，可以是数字、字符、二进制信息、汉字。不能混合数据类型，数据必须经过UTF-8 URL-encoded
     * @param string|integer $widthHeight 生成二维码的尺寸设置
     * @param string|integer $ecLevel 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
     *                            0-默认：可以识别已损失的7%的数据
     *                            1-可以识别已损失15%的数据
     *                            2-可以识别已损失25%的数据
     *                            3-可以识别已损失30%的数据
     *
     * @param string $margin 生成的二维码离图片边框的距离
     *
     * @return string
     */
    public static function toQRimg($text, $widthHeight = 10, $ecLevel = 0, $margin = '0')
    {
        QRcode::png($text, false, $ecLevel, $widthHeight, $margin);
    }

    /**
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 转码字符集转码  仅支持 转码 到 UTF-8
     * @param string $str
     * @param string $targetCharset
     * @return mixed|string
     */
    public static function transcode($str, $targetCharset)
    {
        if (empty($str)) {
            return $str;
        }
        if (strcasecmp('UTF-8', $targetCharset) != 0) {
            $str = mb_convert_encoding($str, $targetCharset, 'UTF-8');
        }
        return $str;
    }

    /**
     * 转成16进制
     * @param string $string
     * @return string
     */
    public static function String2Hex($string)
    {
        $hex = '';
        $len = strlen($string);
        for ($i=0; $i < $len; $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    /**
     * 移除空值的key
     * @param $para
     * @return array
     */
    public static function paraFilter($para)
    {
        $paraFilter = [];
        foreach ($para as $key => $val) {
            if ($val === '' || $val === null) {
                continue;
            } else {
                if (!is_array($para[$key])) {
                    $para[$key] = is_bool($para[$key]) ? $para[$key] : trim($para[$key]);
                }
                $paraFilter[$key] = $para[$key];
            }
        }
        return $paraFilter;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param array $para 需要拼接的数组
     * @return string
     * @throws PaymentException
     */
    public static function createLinkString($para)
    {
        if (! is_array($para)) {
            throw new PaymentException('必须传入数组参数');
        }
        reset($para);
        $arg = '';
        foreach ($para as $key => $val) {
            if (is_array($val)) {
                continue;
            }
            $arg .= $key . '=' . urldecode($val) . '&';
        }
        //去掉最后一个&字符
        $arg && $arg = substr($arg, 0, -1);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * 获取rsa密钥、证书内容
     * @param string $key 传入的密钥信息， 可能是文件或者字符串
     * @param string $type
     * @param string $name ['RSA', 'CERT']
     * @return null|string
     * @throws PaymentException
     */
    public static function getRsaKeyValue($key, $type = 'private', $name = 'RSA')
    {
        if (!in_array($name, ['RSA', 'CERT'])) {
            throw new PaymentException('仅支持RSA与CERT方式');
        }
        if (is_file($key)) {// 是文件
            $keyStr = @file_get_contents($key);
        } else {
            $keyStr = $key;
        }
        if (empty($keyStr)) {
            return null;
        }
        $keyStr = str_replace(PHP_EOL, '', $keyStr);
        // 为了解决用户传入的密钥格式，这里进行统一处理
        if ($name === 'CERT' && $type === 'private') {
            $beginStr = '-----BEGIN PRIVATE KEY-----';
            $endStr = '-----END PRIVATE KEY-----';
        } elseif ($name === 'CERT' && $type != 'private') {
            $beginStr = '-----BEGIN CERTIFICATE-----';
            $endStr = '-----END CERTIFICATE-----';
        } elseif ($name === 'RSA' && $type === 'private') {
            $beginStr = '-----BEGIN RSA PRIVATE KEY-----';
            $endStr = '-----END RSA PRIVATE KEY-----';
        } else {
            $beginStr = '-----BEGIN PUBLIC KEY-----';
            $endStr = '-----END PUBLIC KEY-----';
        }
        $keyStr = str_replace($beginStr, '', $keyStr);
        $keyStr = str_replace($endStr, '', $keyStr);
        $rsaKey = chunk_split($keyStr, 64, PHP_EOL);
        $rsaKey = $beginStr . PHP_EOL . $rsaKey . $endStr;
        return $rsaKey;
    }
}