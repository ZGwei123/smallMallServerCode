<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/3
 * Time: 17:38
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    /**
     * 通过appid获取账号信息
     * @param $appID 授权登录使用api接口的账号名
     */
    public static function getThirdInfoByAppID($appID)
    {
        $thirdAppInfo = self::where('app_id', '=', $appID)
            ->find();
        return $thirdAppInfo;
    }
}