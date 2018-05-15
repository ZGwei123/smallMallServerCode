<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/4
 * Time: 20:09
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    // 获取access_token的url地址
    private $tokenUrl;
    // 缓存access_token的键名
    const TOKEN_CACHE_KEY = 'access';
    // 缓存的access_token的有效时间
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url, config('wx.app_id'), config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    /**
     * 获取access_token，access_token是调用微信请求发送模板消息的凭证，该凭证每天的获取次数是有限的，
     * 且每次获取到的access_token都是有有限期的
     * @return mixed|null
     * @throws Exception
     *
     */
    public function get()
    {
        // 先从缓存中获取
        $accessToken = $this->getFromCache();
        if(!$accessToken){
            // 缓存没有再从新从微信请求获取并缓存
            $accessToken = $this->getFromWxServer();
            return $accessToken;
        } else {
            $accessToken = $this->getFromWxServer();
            $accessToken;
        }
    }

    /**
     * 从缓存中获取access_token
     * @return mixed|null
     */
    private function getFromCache()
    {
        $accessToken = cache(self::TOKEN_CACHE_KEY);
        if($accessToken){
           return $accessToken;
        }
        return null;
    }

    /**
     * 向微信发送请求获取access_token并缓存
     * @return mixed
     * @throws Exception
     */
    private function getFromWxServer()
    {
        $accessToken = curl_get($this->tokenUrl, $httpCode);
        $accessToken = json_decode($accessToken, true);
        if(!$accessToken){
            throw new Exception("获取AccessToken异常");
        }
        if(!empty($accessToken['errcode'])){
            throw new Exception($accessToken['errmsg']);
        }
        // 缓存access_token
        $this->saveToCache($accessToken['access_token']);
        return $accessToken['access_token'];
    }

    /**
     * 缓存access_token
     * @param $accessToken
     */
    private function saveToCache($accessToken){
        cache(self::TOKEN_CACHE_KEY, $accessToken, self::TOKEN_EXPIRE_IN);
    }
}