<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/27
 * Time: 19:31
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = "参数错误";
    public $errorCode = 10000;
}