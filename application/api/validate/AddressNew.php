<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/7
 * Time: 17:45
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];

    protected $message = [
        'mobile' => 'mobile参数值格式错误'
    ];
}