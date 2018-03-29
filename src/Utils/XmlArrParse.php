<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:38
 */

namespace Hongxuan\Smartpay\Utils;


class XmlArrParse
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
     * @param string $widthHeight 生成二维码的尺寸设置
     * @param string $ecLevel 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
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
        $chl = urlencode($text);
        QRcode::png($chl, false, $ecLevel, $widthHeight, $margin);
    }
}