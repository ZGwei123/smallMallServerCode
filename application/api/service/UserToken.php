<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 11:20
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret,
            $this->code);
    }

    /**
     * 获取token
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl, $httpcode);
        $wxResult = json_decode($result, true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                $token = $this->grantToken($wxResult);
                return $token;
            }
        }
    }

    /**
     * 获取token令牌
     * @param $wxResult  调用微信接口返回的信息
     */
    private function grantToken($wxResult){
        $openid = $wxResult['openid'];
        $user = User::getUser($openid);
        if($user){
            $uid = $user->id;
        } else {
            $result = User::add($openid);
            $uid = $result->id;
        }
        $cacheValue = $this->prepareCacheValue($wxResult, $uid);
        $token = $this->saveToCache($cacheValue);
        return $token;
    }

    // 处理token令牌缓存值
    private function prepareCacheValue($wxResult, $uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::USER;
        return $cacheValue;
    }

    /**
     * 微信接口调用失败的异常处理
     * @param $wxResult  微信接口调用后返回的信息
     */
    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}