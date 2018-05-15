<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/3
 * Time: 17:23
 */

namespace app\api\validate;


class UserLogin extends BaseValidate
{
    protected $rule = [
            'ac' => 'require',
            'se' => 'require'
        ];

}