<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 19:01
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    /**
     * 生成Token令牌
     */
    protected static function generateToken(){
        // 获取32位随机字符串
        $randChars = getRandChars(32);
        // 获取时间戳字符串
        $timestamp = $_SERVER['REQUEST_TIME'];
        // salt 盐
        $salt = config('secure.token_salt');
        // 合并三组字符串，用md5加密
        $token = md5($randChars. $timestamp. $salt);
        return $token;
    }

    /**
     * 进行缓存token
     * @param $cacheValue  token令牌的缓存值
     */
    protected function saveToCache($cacheValue){
        // 获取token令牌
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key, $value, $expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;

    }

    /**
     * 获取token令牌对应的缓存值
     * @param $key  缓存数组值其中一个键名
     */
    public static function getTokenVar($key){
        $token = Request::instance()->header('token');
        $value = Cache::get($token);
        if(!$value){
            throw new TokenException();
        } else {
            if(!is_array($value)){
               $value = json_decode($value, true);
            }
            if(array_key_exists($key, $value)){
                return $value[$key];
            } else {
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    /**
     * 获取当前token中的uid
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUid(){
        $uid = self::getTokenVar('uid');
        return $uid;
    }

    /**
     * 获取当前token中的scope
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentScope(){
        $scope = self::getTokenVar('scope');
        return $scope;
    }

    // 用户或CMS管理员权限
    public static function needPrimaryScope(){
        $scope = self::getCurrentScope();
        if($scope >= ScopeEnum::USER){
            return true;
        } else {
            throw new ForbiddenException();
        }
    }

    // 仅用户权限
    public static function needExclusiveScope(){
        $scope = self::getCurrentScope();
        if($scope == ScopeEnum::USER){
            return true;
        } else {
            throw new ForbiddenException();
        }
    }

    // 仅CMS管理员权限
    public static function needSuperScope(){
        $scope = self::getCurrentScope();
        if($scope == ScopeEnum::SUPER){
            return true;
        } else {
            throw new ForbiddenException();
        }
    }
    /**
     * 根据传入的参数uid，对比当前token中的uid来检查是否可以操作（一样表示uid在对自己的数据进行操作）
     * @param $checkUID  被操作数据所属uid
     * @return bool
     * @throws Exception
     * @throws TokenException
     */
    public static function isValidOperate($checkUID){
        if(!$checkUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($checkUID == $currentOperateUID){
            return true;
        }
        return false;
    }

    /**
     * 验证token令牌是否有效
     * @param $token 令牌
     * @return bool
     */
    public static function verifyToken($token){
        $token = Cache::get($token);
        if($token){
           return true;
        } else {
            return false;
        }
    }
}