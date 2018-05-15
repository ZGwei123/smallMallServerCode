<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/7
 * Time: 10:54
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = [
        'img_id', 'delete_time', 'product_id'
    ];

    public function imgUrl(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}