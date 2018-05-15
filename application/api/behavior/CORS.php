<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/5
 * Time: 13:49
 */

namespace app\api\behavior;


class CORS
{
    // 在应用初始化的时候，定义全局允许跨域请求（即所有接口都可以）
    public function appInit(&$param){
        // CORS会先发送一次请求获取确认服务器是否允许跨域请求，不允许就不在发送，允许则会再发送一次真正的请求
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: token,Origin,X-Requested-With,Content-Type,Accept');
        header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');
        // 有些浏览器第一次确认请求可能请求方式为options，需要设置允许跨域后，终止应用的继续执行，
        // 否则接口并没有定义options的请求方式，这时会返回错误引起浏览器不再送第二次请求
        if(request()->isOptions()){
            exit();
        }
    }
}