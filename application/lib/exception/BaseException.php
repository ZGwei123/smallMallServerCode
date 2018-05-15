<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/26
 * Time: 21:00
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    // http状态码
    public $code;
    // 错误码描述
    public $msg;
    // 错误码
    public $errorCode;

    /**
     * 对$code、$msg、$errorCode赋值
     * @param array $param 包含上面三个变量值的数组
     *
     */
    public function __construct($param = [])
    {
        if(!is_array($param)){
            throw new Exception("参数必须为数组");
        }
        if(array_key_exists("code", $param)){
            $this->code = $param['code'];
        }
        if(array_key_exists("msg", $param)){
            $this->msg = $param['msg'];
        }
        if(array_key_exists("errorCode", $param)){
            $this->errorCode = $param['errorCode'];
        }
    }
}