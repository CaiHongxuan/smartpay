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

    'default' => env('PAYMENT_DRIVER', 'alipay'),

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
            'app_id'  => 'your-app-id',
            'app_key' => 'your-app-key',
        ],

        'weixin' => [
            'driver'  => 'weixin',
            'app_id'  => 'your-app-id',
            'app_key' => 'your-app-key',
        ],

    ],

];