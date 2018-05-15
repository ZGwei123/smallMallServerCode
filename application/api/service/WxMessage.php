<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/4
 * Time: 21:15
 */

namespace app\api\service;


use think\Exception;

class WxMessage
{
    // 请求微信发送模板消息的url地址
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s";
    private $touser;
    private $color;

    // 模板id
    protected $tplID;
    // 点击模板跳转页
    protected $page;
    // （是支付还是表单产生的能发送模板消息的id）
    protected $formID;
    // 模板内容
    protected $data;
    // 模板需要放大的关键词
    protected $emphasisKeyword;

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        // 完整url地址
        $this->sendUrl = sprintf($this->sendUrl, $token);
    }

    /**
     * 请求微信发送模板消息
     * @param $openID 微信用户id
     * @return bool
     * @throws Exception
     */
    protected  function sendMessage($openID)
    {
        $data = [
            'touser' => $openID,
            'template_id' => $this->tplID,
            'page' => $this->page,
            'form_id' => $this->formID,
            'data' => $this->data,
            'emphasis_keyword' => $this->emphasisKeyword
        ];

        // 由于发送请求的curl_post方法未编写正确，无法发送请求，返回来的结果是false
        $result = curl_post($this->sendUrl, $data);
        $result = json_decode($result, true);
        if($result['errcode'] == 0){
            return true;
        } else {
            throw new Exception('模板消息发送失败，'. $result['errmsg']);
        }
    }
}