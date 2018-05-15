<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/25
 * Time: 18:00
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        "id" => "require|isPositiveInt"
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];

}