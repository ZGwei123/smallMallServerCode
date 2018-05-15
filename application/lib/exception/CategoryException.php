<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 17:54
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '分类不存在';
    public $errorCode = 50000;
}