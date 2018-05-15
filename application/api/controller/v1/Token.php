<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 11:07
 */

namespace app\api\controller\v1;


use app\api\model\ThirdApp;
use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\api\validate\UserLogin;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenServer;
use app\lib\exception\TokenException;

class Token
{
    /**
     * 获取token令牌
     * @http  POST
     * @url  token/user    code=
     * @param $code
     */
    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $userToken = new UserToken($code);
        $token = $userToken->get();
        return [
            'token' => $token
        ];
    }

    /**
     * 验证令牌
     * @http POST
     * @url token/verify      tokey=
     * @param string $token
     * @return array
     * @throws \think\Exception
     */
    public function verifyToken($token = ''){
        if(!$token){
            new ParameterException([
                'msg' => "token不能为空"
            ]);
        };
        $valid = TokenServer::verifyToken($token);
        return [
          "isValid" => $valid
        ];
    }

    /**
     * 验证用户名和密码，正确时，登录成功并授权token
     * @http POST
     * @url token/app        ac=*&se=*
     * @param string $ac 用户名
     * @param string $se 密码
     * @return array
     */
    public function loginGetToken($ac = '', $se = ''){
        (new UserLogin())->goCheck();
        // 获取信息验证是否登录成功
        $thirdAppInfo = ThirdApp::getThirdInfoByAppID($ac);
        if(!$thirdAppInfo || !($thirdAppInfo->app_secret == $se)){
            throw new TokenException([
                'msg' => '用户名或密码错误'
            ]);
        }
        // 获取token
        $uid = $thirdAppInfo->id;
        $scope = $thirdAppInfo->scope;
        $appToken = new AppToken($uid, $scope);
        $token = $appToken->get();
        return [
            'token' => $token
        ];
    }
}