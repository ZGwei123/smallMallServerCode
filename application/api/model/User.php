<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 11:18
 */

namespace app\api\model;


class User extends BaseModel
{

    public function address(){
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    /**
     * 添加一条user数据
     * @param $openid  openid字段值
     */
    public static function add($openid){
        $result = self::create([
           'openid' => $openid
        ]);
        return $result;
    }

    /**
     * 获取一条user数据
     * @param mixed|null $openid  openid字段值
     */
    public static function getUser($openid){
        $user = self::where('openid', '=', $openid)
            ->find();
        return $user;
    }
}