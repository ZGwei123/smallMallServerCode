<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 19:19
 */
return [
    'token_salt' => 'HHsTieBU377mJtKr',

    // 关于千支付的使用请查看官网文档
    // 千应支付参数配置
    'QRCodeBaseUrl' => 'https://www.qianyingnet.com/%s',  // 获取二维码的url
    'url' => 'https://www.qianyingnet.com/pay?uid=%s&type=%s&m=%s&orderid=%s&callbackurl=%s&sign=%s&gotrue=%s&gofalse=%s&charset=%s&token=%s',  // 千应上行接口url（发起支付请求）
    'uid' => '',    // 商户ID
    'key' => '',   // 密钥
    'callbackurl' => 'https://api.fenyue.net.cn/api/v1/pay/process_notify',   // 千应在用户支付后对服务器异步回调通知支付结果
    'gotrue' => '',   // 用户支付成功跳转页
    'gofalse' => '',   // 用户支付失败跳转页
    'price_expire_in' => 360      // 价格在缓存的有效期（如果用户拿到二维码后一直未支付，过了有效期后，将删除缓存中的价格，使同等价格的订单可以被支付）
];