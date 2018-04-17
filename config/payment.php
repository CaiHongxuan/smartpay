<?php

return [

    /*
    |--------------------------------------------------------
    | Default Payment Driver
    |--------------------------------------------------------
    |
    | Supported: "alipay", "weixin"
    |
    */

    'default' => env('PAYMENT_DRIVER', 'weixin'),

    /*
    |--------------------------------------------------------
    | Payment Drivers
    |--------------------------------------------------------
    |
    | Here you may configure the driver information for each
    | driver that is used by your application. A default
    | configuration has been added. You are free to add more.
    |
    */

    'drivers' => [

        'alipay' => [
            'driver'            => 'alipay',

            // 应用ID,您的APPID。
            'app_id'            => "your-APPID",

            // 支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => 'your-支付宝公钥',

            // 商户私钥
            'private_key'       => ' your-商户私钥',

            // 异步通知地址
            'notify_url'        => 'http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php',

            // 同步跳转
            'return_url'        => 'http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php',

            // 编码格式
            'charset'           => "UTF-8",

            // 调用的接口版本，固定为：1.0
            'version'           => '1.0',

            // 仅支持JSON
            'format'            => 'JSON',

            // 签名方式
            'sign_type'         => 'RSA2',

            // 支付宝网关
            'gatewayUrl'        => 'https://openapi.alipay.com/gateway.do',
        ],

        'weixin' => [
            'driver'       => 'weixin',

            // AppId
            'app_id'       => 'your-AppId',

            // AppSecret
            'app_key'      => 'your-AppSecret',

            // 商户id
            'mch_id'       => 'your-mch_id',

            // 商户私钥
            'private_key'  => 'your-private_key',

            'app_cert_pem' => '微信支付证书cert',

            'app_key_pem'  => '微信支付证书key',

            // 微信支付证书保存地址
            'cert_path'    => '',

            // 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
            'fee_type'     => 'CNY',

            // 签名方式
            'sign_type'    => 'MD5',

            // 如果是H5支付，可以设置该值，返回到指定页面
            'redirect_url' => '',

            // 异步通知地址
            'notify_url'   => "http://外网可访问网关地址/WxpayAPI_php_v3.0.1/example/notify.php",
        ],

    ],

];