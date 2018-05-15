<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/25
 * Time: 19:38
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use app\lib\exception\UserException;
use think\Request;
use think\Validate;

// 将所有操作方法中需要进行参数验证的代码块提出来构成验证层的基类
class BaseValidate extends Validate
{
    // 进行http参数验证
    public function goCheck(){
        // 获取http参数
        $data = Request::instance()->param();
        // 进行验证
        $result = $this->batch()->check($data);
        if(!$result){
            // 获取验证错误内容
            $error = $this->error;
            // 参数错误时，抛出异常返回错误信息给客户端
            $parameterException =  new ParameterException([
                "msg" => $error
            ]);
            throw $parameterException;
        } else {
            return true;
        }
    }

    /**
     * 从客户端传入的参数中获取在$rule规则定义验证需要的参数
     * @param $urlParam  url传入的参数
     */
    public function getDataByRule($urlParam){
        if(array_key_exists('user_id', $urlParam) ||
            array_key_exists('uid', $urlParam)){
            throw new ParameterException([
                'msg' => '参数中含有非法的参数名user_id或uid'
            ]);
        }
        $dataArray = [];
        // 获取需要的参数
        foreach($this->rule as $key => $value){
            $dataArray[$key] = $urlParam[$key];
        }
        return $dataArray;
    }

    // 自定义一个正整数规则
    protected function isPositiveInt($value, $rule = '', $data = '', $field = '')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        } else {
            return false;
        }
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = ''){
        if(empty($value)){
            return false;
        } else {
            return true;
        }
    }

    // 自定义一个电话号码规则
    protected function isMobile($value, $rule = '', $data = '', $field = ''){
        $ruleTel = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $ruleMob = '^([0-9]{3,4}-)?[0-9]{7,8}$^';
        $resultTel = preg_match($ruleTel, $value);
        $resultMob = preg_match($ruleMob, $value);
        if($resultTel || $resultMob){
            return true;
        } else {
            return false;
        }
    }
}