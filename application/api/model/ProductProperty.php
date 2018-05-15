<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/7
 * Time: 10:54
 */

namespace app\api\model;


class ProductProperty extends  BaseModel
{
    protected $hidden = [
        'product_id', 'delete_time'
    ];
}