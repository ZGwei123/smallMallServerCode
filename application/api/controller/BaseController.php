<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/9
 * Time: 16:26
 */

namespace app\api\controller;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    // 前置方法（进行权限认证）
    public function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    public function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }

    public function checkSuperScope(){
        TokenService::needSuperScope();
    }
}