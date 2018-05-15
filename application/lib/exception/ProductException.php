<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 12:42
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '指定的商品不存在，请检查参数';
    public $errorCode = 20000;
}