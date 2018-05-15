<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/7
 * Time: 17:36
 */

namespace app\api\controller\v1;


use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\BaseException;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Controller;

class Address extends BaseException
{
    // 定义前置
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getAddress']
    ];

    /**
     *  添加或更新一条用户地址信息
     * @http  POST
     * @url  /address
     */
    public function createOrUpdateAddress(){
        // 用验证器进行参数验证
        $validate = new AddressNew();
        $validate->goCheck();
        // 从token中获取用户uid
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid, 'address');
        if(!$user){
            throw new UserException();
        }
        // 从url参数中获取需要的address数据参数
        $dataArray = $validate->getDataByRule(input('post.'));
        // 添加或更新address
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        } else {
            $user->address->save($dataArray);
        }
        // 返回给一个json格式的成功信息
        return json(new SuccessMessage(), 201);
    }

    /**
     * 获取用户收货地址
     * @http GET
     * @url address
     */
    public function getAddress(){
        $uid = TokenService::getCurrentUid();
        $address = UserAddress::getAddress($uid);
        if(!$address){
            throw new UserException([
                "msg" => "用户收货地址不存在",
                "errorCode" => 60001
            ]);
        }
        return $address;
    }
}