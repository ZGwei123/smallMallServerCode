<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/3
 * Time: 9:30
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须是一个或多个由英文逗号分隔的正整数'
    ];

    // 自定义一个由正整数拼接成的字符串的规则
    public function checkIDs($value, $rule = '', $data = '', $field = ''){
        if(empty($value)){
            return false;
        }
        $ids = explode(',', $value);
        foreach($ids as $id){
            if(!$this->isPositiveInt($id)){
                return false;
            }
        }
        return true;
    }
}