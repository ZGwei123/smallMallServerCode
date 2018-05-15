<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 19:33
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'token令牌已过期或无效';
    public $errorCode = 10001;

}