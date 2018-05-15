<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/7
 * Time: 22:39
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = [
        'delete_time'
    ];
    /**
     * 获取用户id的地址信息
     * @param $uid  用户id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAddress($uid){
        $address = self::where('user_id', '=', $uid)
            ->find();
        return $address;
    }
}