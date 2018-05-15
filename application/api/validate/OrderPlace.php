<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/9
 * Time: 17:27
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    // 商品列表验证
    protected $rule = [
        'products' => 'checkProducts'
    ];

    // 每个商品验证，不会自动验证，需当参数传入验证对象
    protected $singRule = [
        'product_id' => 'require|isPositiveInt',
        'count' => 'require|isPositiveInt'
    ];

    // 商品列表验证规则
    protected function checkProducts($value, $rule = '', $data = '', $field = ''){
        if(!is_array($value)){
            throw new ParameterException([
               'msg' => '商品参数错误'
            ]);
        }
        if(empty($value)){
            throw new ParameterException([
               'msg' => '商品列表不能为空'
            ]);
        }
        // 对每个商品参数进行验证
        foreach($value as $product){
            $this->checkProduct($product);
        }
        return true;
    }

    // 商品验证规则
    protected function checkProduct($product){
        // 创建验证对象，传入一个验证字段数组
        $validate = new BaseValidate($this->singRule);
        // 进行验证
        $result = $validate->check($product);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}