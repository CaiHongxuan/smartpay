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
            'driver'  => 'alipay',

            //应用ID,您的APPID。
            'app_id' => "2018032302434998",

            //商户私钥
            'mpk' => "MIIEowIBAAKCAQEAzm7ZW5AFFdaF3cUTyTWIIA4fjALXzgs4S+2f+0CcnDZhw\/+G\n+dJ2CZ2j+69txg5JbyLrhBd5nV2x\/D9QC9Ft2ozYfa9\/MQTCqaRObWpZhMyLDDVV\nkc3kxTtIMWX7BTorkTi4tjZFiy\/yGSLkpwQLbCnVHD5l+MOHmB\/qspR0aj1mY8eu\n0bcIL3Djn5Y1liK5I2m+SVK7e9ls0CvtVYcXXY069hvo7em+dhohZO6vbWEwImPU\nqNb\/mz06soqoW4iDSwQvGGQRRpXm6pNZgE3GLLhHVl3FTN6AUb1PgaS1X7w2V4Fi\n86A46zOrunrw\/JOfz3IvJLB0MqT5Tjf2xVsMjQIDAQABAoIBAGkmIWT4OP7kpI9P\n4UIuGEZrqzoAALidEnHqegDa6mrPcIELWU9LGoDPYUXEF+A4SsNnQBuqcXHs1cos\n3bMHXDNkZqmuiNUJHbaXULN+5lY0cecoC4wXGh40khNHZNNGzAZOpBd8EXRSWbjg\nAyPndYIE1N5sEjjiL+EW8M9BuwKZlULJDDbpxNrzfjFPOvByj1HnKoibPkECTC5N\n7Wl0g7CN4XCanMWD\/w\/Jz3eVRnlbCqlSVoygbvJYxbVVCChmltrQ1s1Eqo2nFTF0\nKjpFX5+aBDL9ZhLqRCektWbXnOVMxsFMJPKRbf54AH+5ocKKgfcDsCjDdor+nc0J\n9S3\/NGkCgYEA89Oe8y5R2Md0i6CCLywlMHH2mmqntzJO+7RpIbJqVEbTiT2ZaQHT\n+KAf0MLit1KRYm\/iqq46VzaeTsRS+fBjjuZOLbVZLaYB9rcxouj2WWUV5HEbTJnm\noRxDqaVNHkv2U4+JyXJ8Z44t667zIMxiqJ6t4xzDKhVHVCDWIp6hkVsCgYEA2L1L\nml8NDpvew5lRT+39WghZUtXV1S78tpVLmAALIWBNnDLH4GEiwmL0UtyRwjgImTl7\nsH9pqdwGtfTqE7J1YB0tv8GmI0agJ+cc2Y69e29ibhWTgdpt5uTK8cz3UWglzozd\nTyB+qH1K9hy5FOJR9953xMfNo1R+4C8GbiQlFjcCgYAr13lVMJb266ZBFPNTmBR3\nyYYV7eeKQTmCeMYOkQ4daT7QBot3HiUHJ4OQ\/fnWDLqngIa5Oeqzk9aTQynIVzkP\nkMmt3lXSp27i9b2vFUR2fn6ZPN0zwNh\/T526+0YKlI4OKFkWJ8fGtwH6xtJgq19y\nAlYx6BIPlxPkRKM8Rlm20QKBgEwsUzrg9xzQST1jUAGQef8\/ee0z\/CAdkHG3DMdS\nFJdZWk1wR3EjkmzRDF9m589jlBoN6tvAG+m3y5\/9gJsFmFz8REzliTdSpg0AG9KW\naR\/NqMFZ0erMUt3YQT80MyOoP8lVFp6to\/Yhesv+kkm1tOsxg9RkCHDDzIeL322s\nRGCHAoGBAPFJhqWTy8wdabRnkUAww6Fe4r4SFlPKy7KomoxU5W8MmW0zZNe1WJvK\nfB8JCKwzs9lenBttfHecpJUUa03CO\/PZwuCuUPdcIzIIcH\/piggot\/bkQfYlsehb\n4XA3CU0FmLoHB8Gk90tzd9bYsU9GGBTy9ra3YLHTzqyFG1l26DFq",

            //异步通知地址
            'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",

            //同步跳转
            'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqtlMiPnjdi6ANzbRKtguj5Vb8+UCr8lpaOeTqxYwSHBR7pL2NNPonkta0iD+lM9JBHwjqqzW89WTl15hUkTLlw/uEkqsxZmBmGFCn9gleQinlasbXCC+n/3MB/tRJGHOP7GOvmw9MGinrst6k22M2UbmxMiopdCeIr0IBr76r7TT7nuURIoDhx7nBVVrLAcU4pk0iXo+EWMp1nsKn07wGGMFAFhrSQfEJsv+GcyH1FsqW5riLzNDT6b8qn+AgrckQ9x600OmBHMsnzQrJbOYhptDFLDzFsEH+dMzjIRrttzZNCrpOl0P+20xCcf2pZSwbA5mcO+yr08bALX+WWyz8wIDAQAB",
        ],

        'weixin' => [
            'driver'  => 'weixin',
            'app_id'  => 'your-app-id',
            'app_key'  => 'your-app-key'
        ],

    ],

];