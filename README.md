# smartpay
用于Laravel的第三方支付工具包，使用IOC容器技术，支持composer加载。整合支付宝、微信等支付方式

# 目前实现的功能
1. 支付宝网页扫码支付
2. 支付宝移动端支付
3. 支付宝交易查询
4. 支付宝交易退款
5. 支付宝交易退款查询
6. 支付宝交易关闭
7. 支付宝对账单下载
8. 微信扫码支付
9. 微信公众号支付
10. 微信交易查询
11. 微信交易退款
12. 微信交易退款查询
13. 微信交易关闭
14. 微信对账单下载

# 使用例子
```php

    // 支付宝支付
    $result = Payment::driver('alipay')
        ->setPayType('ali_web') // 可不设置此参数，默认为“ali_web”（网页扫码）支付方式，“ali_wap”为移动端支付
        ->setOrder([
            'body'         => '商品描述',
            'subject'      => '订单名称',
            'total_amount' => 0.01,   // 支付金额
            'out_trade_no' => '1010', // 商户订单号
        ])
        ->setNotifyUrl('http://www.baidu.com') // 异步通知地址，公网可以访问，可不设置此参数，默认采用配置文件的"notify_url"参数
        ->setReturnUrl('http://www.baidu.com') // 同步跳转地址，公网可访问，可不设置此参数，默认采用配置文件的"return_url"参数
        ->pay();
        
        
    // 微信扫码支付（用户打开扫一扫，扫码商户二维码完成支付）
    $result = Payment::driver('weixin')
        ->setPayType('wx_qr')// 可不设置此参数，默认为“wx_qr”（扫码）支付方式
        ->setOrder([
            'body'         => '商品描述',
            'detail'       => '商品详情', // 商品详情，非必填
            'total_amount' => 0.01,   // 支付金额
            'out_trade_no' => '0101', // 商户订单号
            'product_id'   => '0101', // 商品ID
            'attach'       => 'test', // 附加数据，非必填，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
            'time_start'   => date('YmdHis'), // 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
            'goods_tag'    => 'test', // 商品标记，非必填，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
        ])
        ->setNotifyUrl('http://www.baidu.com') // 异步通知地址，公网可以访问
        ->pay();
        
        
    // 微信公众号支付（用户在微信内进入商家的H5页面，页面内调用JSSDK完成支付）
    $result = Payment::driver('weixin')
        ->setPayType('wx_pub')// 可不设置此参数，默认为“wx_qr”（扫码）支付方式
        ->setOrder([
            'body'         => '商品描述',
            'detail'       => '商品详情', // 商品详情，非必填
            'total_amount' => 0.01,   // 支付金额
            'out_trade_no' => '0102', // 商户订单号
            'product_id'   => '0102', // 商品ID
            'attach'       => 'test', // 附加数据，非必填，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
            'time_start'   => date('YmdHis'), // 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
            'goods_tag'    => 'test', // 商品标记，非必填，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
            'openid'       => 'odWrUwmRxJpPsnGpKP4CXKkvPLQ0', // '用户在商户appid下的唯一标识,公众号支付,必须设置该参数
        ])
        ->setNotifyUrl('http://www.baidu.com')// 异步通知地址，公网可以访问
        ->pay();

```

```php

    // 支付宝交易查询
    $result = Payment::driver('alipay')
        ->setOrder([
            'trade_no'     => '',     // 支付宝交易号
            'out_trade_no' => '0101', // 商户订单号
        ])
        ->tradeQuery();
        
        
    // 微信交易查询
    $result = Payment::driver('weixin')
        ->setOrder([
            'trade_no'     => '',     // 微信交易号
            'out_trade_no' => '0101', // 商户订单号
        ])
        ->tradeQuery();

```

```php

    // 支付宝交易退款
    $result = Payment::driver('alipay')
        ->setOrder([
            'refund_amount'  => 0.02,       // 退款金额
            'trade_no'       => '',         // 支付宝交易号
            'out_trade_no'   => '0101',     // 商户订单号
            'out_request_no' => '0101',     // 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
            'refund_reason'  => '退款理由', // 退款原因
        ])
        ->refund();
        
        
    // 微信交易退款
    $result = Payment::driver('weixin')
        ->setOrder([
            'total_amount'   => 0.02,   // 订单总金额
            'refund_amount'  => 0.02,   // 退款金额
            'trade_no'       => '',     // 微信交易号
            'out_trade_no'   => '0101', // 商户订单号
            'out_request_no' => '0101', // 设置商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
            'refund_reason'  => '退款理由', // 退款原因，非必填
        ])
        ->refund();

```

```php

    // 支付宝交易退款查询
    $result = Payment::driver('alipay')
        ->setOrder([
            'trade_no'       => '',     // 支付宝交易号，优先级：trade_no > out_trade_no
            'out_trade_no'   => '0101', // 商户订单号
            'out_request_no' => '0101', // 请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号，必填
        ])
        ->refundQuery();
        
        
    // 微信交易退款查询
    $result = Payment::driver('weixin')
        ->setOrder([
            'trade_no'       => '',     // 微信交易号，二选一，优先级：trade_no > out_trade_no
            'out_trade_no'   => '0101', // 商户订单号
        ])
        ->refundQuery();

```

```php

    // 支付宝交易关闭
    $result = Payment::driver('alipay')
        ->setOrder([
            // trade_no，out_trade_no不可同时为空，优先采用trade_no
            'trade_no'     => '',     // 支付宝交易号
            'out_trade_no' => '0101', // 商户订单号
        ])
        ->close();
        
        
    // 微信交易关闭
    $result = Payment::driver('weixin')
        ->setOrder([
            // trade_no，out_trade_no不可同时为空，优先采用trade_no
            'trade_no'     => '',     // 微信交易号
            'out_trade_no' => '0101', // 商户订单号
        ])
        ->close();

```

```php

    // 支付宝对账单下载
    $result = Payment::driver('alipay')
        ->setOrder([
            // 账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；
            // trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
            'bill_type' => 'signcustomer',
            // 账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
            'bill_date' => date('Y-m', strtotime('2017-09-16 10:10:10'))
        ])
        ->download();
        
        
    // 微信对账单下载
    $result = Payment::driver('weixin')
        ->setOrder([
            // 设置ALL，返回当日所有订单信息；默认值SUCCESS，返回当日成功支付的订单；REFUND，返回当日退款订单；REVOKED，已撤销的订单
            'bill_type' => 'ALL', 
            // 设置下载对账单的日期，格式：yyyyMMdd，如：20140603
            'bill_date' => date('Ymd', strtotime('2017-11-16 10:10:10')) 
        ])
        ->download();

```
