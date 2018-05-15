<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/3
 * Time: 19:49
 */

namespace app\api\service;


class AppToken extends Token
{
    protected $uid;
    protected $scope;

    public function __construct($uid, $scope)
    {
        // 对token缓存值的数据初始化
        $this->uid = $uid;
        $this->scope = $scope;
    }

    /**
     * 获取token
     * @return string
     * @throws \app\lib\exception\TokenException
     */
    public function get(){
        // token缓存值
        $cacheValue = ['uid' => $this->uid, 'scope' => $this->scope];
        // 缓存获取token
        $token = $this->saveToCache($cacheValue);
        return $token;
    }
}