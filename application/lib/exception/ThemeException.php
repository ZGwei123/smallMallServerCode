<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/3
 * Time: 16:28
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定的主题不存在，请检查主题ID';
    public $errorCode = 40000;
}