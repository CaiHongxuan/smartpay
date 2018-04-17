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
            'app_id'            => "2017080408028984",

            // 支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn7w5W2s260OfTdzraolo6qNP6CEMJV3eSe5czGVqMJ6I3Fy12hm9US9qBsfWh7oYGpllXQc4rTcbKTrZdJKPFuv60NGkH6VoL0Qlcaggi0IGpuCDD5QPLfGQ3Pj6+Y8E8Lom5QRL4eNj59buht2y5zgysGIjTHuT31MtROAvKH2nnaglZ/V7kPGsEvFLtoY89VTlViJOh8d4wUXhoN0A8eIr+fcPA/xS1AwQqqyqjNAGI8KoiFpR/mrz2Ju2vgLSxT69ao/zGfaqZj1XQo0I+hUL0xCRApIIOCPopu8+ZMybPjPzHQTAsJL/X1X2W33RR1Ex7E9tDv36QSM0lOwJuQIDAQAB',

            // 商户私钥
            'private_key'       => 'MIIEpQIBAAKCAQEA3fwPL0PJMyTWqa+fpKAfvN6uAI0OkW7MsIzTAM2JMZ48bnE0NOooT9IZ06QVhr0iibDU5Iw13Bdt5rmIQU+0aN9n8E64Er8ftqp+hOMHC+/7VwPU+WxBwOY7T1imjNF3UjvRML7I/A5TZT97zUOM9dmIxOIX1f7w2qilcIuTxpdflTb1WFxHKEIkYYCjfoo5nQg6tLXxYHvHyXiQXB3UDsUerkXrJI+F1B8xPw/obHzMdW4DZ7cyo9OhqNcwduHGO/q8uP+Nu93N5bECiubigb5jgnUhz38XpBUn17H8mffIIpwSFusv75tifviEoKI5mnI15Hx1fPX7FX0o6shdYQIDAQABAoIBAQCg20Adnd80QmOTPoJOhwG4mRw5pf2CgWmuHb3g/Q+HdwSPe1S7a1qezL6OUH6QzokygYMjwj5dKFUpNhR4T0uKGyl0R3a3jutqMI3RubmnetUErvArdbkIEU21J6Y4sKjoXBQwYG+/xpnD6obJrUN9+45SLQvctArQSBjqPxpscnZC8PXHQsrH8Pe6KJJkcskezzHv4SzOt9GLEbu9XZR5DoVZOV7tddrWtcSKvb3OuMhKA6HTNK3gWjecakSKYC9TWC9L7XQimKQ5Rmlhj8zCZQu6HpWofNFS4tngEFVy3wuvEsHKIVefptqUEpPeuOXvepTJ2LvyUrkJUhfX5PIBAoGBAP6SZptEYu4Rp2UNWpz44vx5qhO/mJfOhmPIPVvWc+HCWxg9Kg0roZnMYVu/XvMYAYzuZvBcWboTuSLdF2lZWuYeuLv8f4cAJd7ranrPdiaqf3guN47LXWJcGJkzcqbASrvj4MYrv8zuQaBtNUgt4RJ+fsDmFDeqXYiduj+oVobxAoGBAN8629zBhue4R+2H6xel3zpem97hFoLJjc2lKeAZac9U4gHULzJtjm5fQpvTy0LzyBHVFYWnreSbUMHiTdj3L1gnp09+FWZUfrPPjpEXJlo3QOnCbwSk9psYYDFu25GPmzk2fg35yyIj3peboyxQA2ZZipNtReTGXbFlxa+7JZ1xAoGBAL34bk1ryQ+zaOGGB5qgOHMEL6ExFyQh4DPSF8fSzwMn0GbULe9KIfvtgrG+q5Jo1a9fsL2pjOPJGB0mM/RP0/9p6Z2PHXOW7qvdrcYbzyWnkhwTES6kH/nolAqvU92QHbT8pp37w9Of8KVRGbPVWOI+N0Sn7Wpk3gu2+GfMrVVhAoGBAJqtaxk9E+BOJbDmJDUfn10Pn0vBhdqcFGDxV+HLWjDqrSv9PbLgjPfXlAzrpYU/7FrG3oHdHTYxlLSzvaNgK/MWju0a/XMJiz3GzQ+mDdInRRh0vH5oW+Q98LFwEj57VmA/bPr8IhAG8L72fgs/aguqccYTyoFqHhPE5EUPFVJRAoGAeYA1AKKeeqLr/AhG6p0d/NeUteb75X/rLi2/jlIYka+HH1XrM9B+M3obLU3S2Q0G79nH/lsSOCmfDvssPiVa8h1qKMj7jgHV49zKJ14nQJ6wB8j8ZSu9FglmcQQdtB5NbgrfL5qmdJ1suvjHplhOGHnUY4U+1GbAHHSd8DbHbBQ=',

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
            'app_id'       => 'wx9c5a220e17f03ab1',

            // AppSecret
            'app_key'      => '23619921234c4f7487937db68c737ff2',

            // 商户id
            'mch_id'       => '1486973282',

            // 商户私钥
            'private_key'  => 'TeORXHESNoEKWPMiRYEEhPfo6ow9AA9y',

            'app_cert_pem' => 'MIIEXzCCA8igAwIBAgIDEN9AMA0GCSqGSIb3DQEBBQUAMIGKMQswCQYDVQQGEwJDTjESMBAGA1UECBMJR3Vhbmdkb25nMREwDwYDVQQHEwhTaGVuemhlbjEQMA4GA1UEChMHVGVuY2VudDEMMAoGA1UECxMDV1hHMRMwEQYDVQQDEwpNbXBheW1jaENBMR8wHQYJKoZIhvcNAQkBFhBtbXBheW1jaEB0ZW5jZW50MB4XDTE2MDIwMzExNDg1OVoXDTI2MDEzMTExNDg1OVowgY8xCzAJBgNVBAYTAkNOMRIwEAYDVQQIEwlHdWFuZ2RvbmcxETAPBgNVBAcTCFNoZW56aGVuMRAwDgYDVQQKEwdUZW5jZW50MQ4wDAYDVQQLEwVNTVBheTEkMCIGA1UEAxQb6Ieq5Yqo5YyW5rWL6K+V5ZWG5oi35ZCN56ewMREwDwYDVQQEEwgxMTM4NDEzMzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALQ/qiPEwzmDLkWQhwA1Td6YQh1BxhgxH244rdpyfiiXv/m/QbYGfkJ27EZiNOkRtZg0kOh4XGfo99bQwia+SSxPsajtnTwbGOYPKRP4Xc44SlFR9n9v3N5XzLJSXZrvlKnz3Cf7PdHRTXxs0w0gsubMTu2P0MACLfUw11IPtGisx+SGMlgjGZ20q6suYF+RydUTXvHelo9R/HFfyV3RPSZryOHP1CtKMh+H1DOwdwF+d/ZIY2nkdS9HBe3Q2QD1/Po6z1hD6LAnAdggGOyXjLNsSgkQwizQdf5Xc6xxIgLfEZlzHOM5ndLbLPovm+yPcilvm1qu7AeKs/qodj5FU9cCAwEAAaOCAUYwggFCMAkGA1UdEwQCMAAwLAYJYIZIAYb4QgENBB8WHSJDRVMtQ0EgR2VuZXJhdGUgQ2VydGlmaWNhdGUiMB0GA1UdDgQWBBSRQB0ev//y8tmCeOhM6YdhH88DGzCBvwYDVR0jBIG3MIG0gBQ+BSb2ImK0FVuIzWR+sNRip+WGdKGBkKSBjTCBijELMAkGA1UEBhMCQ04xEjAQBgNVBAgTCUd1YW5nZG9uZzERMA8GA1UEBxMIU2hlbnpoZW4xEDAOBgNVBAoTB1RlbmNlbnQxDDAKBgNVBAsTA1dYRzETMBEGA1UEAxMKTW1wYXltY2hDQTEfMB0GCSqGSIb3DQEJARYQbW1wYXltY2hAdGVuY2VudIIJALtUlyu8AOhXMA4GA1UdDwEB/wQEAwIGwDAWBgNVHSUBAf8EDDAKBggrBgEFBQcDAjANBgkqhkiG9w0BAQUFAAOBgQADwihpkyMaJTaSII48fFz2QbuR14op8CDqYBfF1VKRUahqFWsNEJJ+3KgRLkphwfVWSa7z1Q9EiBCGpKTIug7ER/ZPJUVRXZHbIkveGGV5PmBjAD544McjXHO8PGJ3AubD/iXFwYtHmLDwME8W5nBNnaKkV4+uSPzg8UrBWbCfEw==',

            'app_key_pem'  => 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC0P6ojxMM5gy5FkIcANU3emEIdQcYYMR9uOK3acn4ol7/5v0G2Bn5CduxGYjTpEbWYNJDoeFxn6PfW0MImvkksT7Go7Z08GxjmDykT+F3OOEpRUfZ/b9zeV8yyUl2a75Sp89wn+z3R0U18bNMNILLmzE7tj9DAAi31MNdSD7RorMfkhjJYIxmdtKurLmBfkcnVE17x3paPUfxxX8ld0T0ma8jhz9QrSjIfh9QzsHcBfnf2SGNp5HUvRwXt0NkA9fz6Os9YQ+iwJwHYIBjsl4yzbEoJEMIs0HX+V3OscSIC3xGZcxzjOZ3S2yz6L5vsj3Ipb5taruwHirP6qHY+RVPXAgMBAAECggEAb+iLCLQUBTQV2WjW+GEf3JCpk6KPi9uLyRH1loe5HhjBPxzofkvfvgI5xaUZdo7hMQOJ6Fs5++WfYkawE//WTGWaRuhn07Z7KfLFrTlpfCxkr8J0iUB5X64hT6FlrlkK8s2NpWEOS6NoOVUTX7YqfLLiWgoNL/jqca2GMdPATa/VmZj/irYVBmkFbh7gUWWw7cYUwDJsc5jZkEB/wWwOHG8MzyMoOMttuX2Xt+5t36t7mon5a4u7zM7ctrRbONu3oXxmfqfhgvwgKKrcHOptjT3iF0DOoopGlkimRnVd672h5CRzaBuV+71CrAYB0vE6WhhUQwnUylYUvFLqzR1EgQKBgQDYDkzPPNG6lJMto/4NGH3wtbI5Kad83eymQfeUQrtd8UUA7cYNWEXHZVWs+/fTu93DAIjOESoyWqpcyw4PP31CRT7I3fc6PIhEtnvscpK0ln+cq71QIX8Mcie5W3BuoIcTjvsPY3zM9pSZB9ReLkO9PY7MMRk9GMcsG+lSXN1clwKBgQDVkqX5M5scWpnO3kj0So6GyZScG2IWQ44C0guTZh7dlmzhfwBmXh77sXYNFdIu2eGkGsPBRxqQl3QDdG6bSm2YqwF5VlplabnCq1oxHwt90u0L2441wXLWwquhrxvdlnQrJz0IqD476q6WqBeRxLGpIpkztOSwOK261GlkCtRqwQKBgBjKi0W8VNRz9+9kweH+zXSxZKHqha1uSZlKOH5qqdU9ug1BO1iMqHUYy5vtzaIeDHQzu37puU3N2X6MTjCxuE3CZFHoJlYoW/qGdfHLs8nE+x+fFTn8nfdvod9C/sOy58z2uxgo8kkSgjqNC3FDHcK5LYmAmMTJ8xC8oykwPrZBAoGAO4ncVzJ5xVfElRUGxYObZBwCH9rKZ2aBymt/6qGHbUKoK9zZ4a/Pd18rh85Tf9ghvTvw4orN7w0pvGTTCNug3fSePpNCNA9bR9e5FwSOkY8hojKc3IOHXjN64WINpKJy1CzmKOmuH8n2ze0iVPK+jGYmy3FcZ3wFgpYAo3EZcoECgYBWlBWUK3CtT12uoRdepNWgDCPm0k7KDW71NqiXkA+jxGxsCcv4M3CuN2Xs//2dTWqErhWqMq7ASmfxumCmPHWsdmjya/fGc6G3IrZNj6/fxPrOLShHDBS1HP/9MmVTBd0d39CaSKBaMzvU3DaJu0rqd0IqLGwMv/t411orp77J1Q==',

            // 微信支付证书保存地址
            'cert_path'    => '',

            // 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
            'fee_type'     => 'CNY',

            // 签名方式
            'sign_type'    => 'MD5',

            // 如果是H5支付，可以设置该值，返回到指定页面
            'redirect_url' => 'https://caihongxuan.github.io/',

            // 异步通知地址
            'notify_url'   => "http://外网可访问网关地址/WxpayAPI_php_v3.0.1/example/notify.php",
        ],

    ],

];