<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/26
 * Time: 16:40
 */

namespace app\api\model;


use think\Db;
use think\Exception;
use think\Model;

class Banner extends BaseModel
{
    // 隐藏部分字段
    protected $hidden = ['update_time', 'delete_time'];

    /**
     * 关联模型（关联BannerItem）
     */
    public function items(){
        return $this->hasMany("banner_item", "banner_id", "id");
    }

    /**
     * 通过$id获取banner信息
     * @param $id banner的主键id
     *
     */
    public static function getBannerById($id)
    {
        $banner = self::with(['items', 'items.img'])->find($id);
        return $banner;
    }
}