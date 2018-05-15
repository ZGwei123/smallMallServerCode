<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 12:27
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInt|between:1,15'
    ];

    protected $message = [
        'count' => '参数count必须为1-15的正整数'
    ];
}