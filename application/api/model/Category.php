<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 17:42
 */

namespace app\api\model;



use traits\model\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;
    protected $hidden = [
        'update_time', 'delete_time', 'topic_img_id'
    ];

    public function img(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    /**
     * @return 所有分类
     */
    public static function getAll(){
        $categories = self::all([], 'img');
        return $categories;
    }
}