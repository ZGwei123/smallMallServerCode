<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 12:08
 */

return [
    'app_id' => '', // 在这输入微信appid
    'app_secret' => '',  // 在这输入微信key
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'notify_url' => 'http://www.z.cn/api/v1/pay/notify',
    // 微信获取access_token的URL地址
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s'
];