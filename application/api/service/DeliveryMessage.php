<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/4
 * Time: 21:34
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
    // 模板id
    const DELIVERY_MSG_ID = "7LC9viHtwpc4Y-TBMujmJUm8w0y1Cnxx-srSc5olHm0";

    /**
     * 发送请求微信发送发货模板消息给用户
     * @param $order 订单
     * @param string $tplJumpPage 点击模板跳转到小程序指定页面
     * @return bool
     * @throws OrderException
     * @throws \think\Exception
     */
    public function sendDeliveryMessage($order, $tplJumpPage = '')
    {
        if(!$order){
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyword = "keyword2.DATA";
        // 发送请求
        return $this->sendMessage($this->getUserOpenID($order->user_id));
    }

    /**
     * 从订单信息中获取数据赋值到模板内容
     * @param $order 订单
     */
    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $address = $order->snap_address;
        $data = [
          'keyword1' => [
              'value' => $dt->format("Y-m-d H:i:s")
          ],
          'keyword2' => [
              'value' => $order->order_no,
              'color' => '#274088'
          ],
          'keyword3' => [
              'value' => $address['name']
          ],
          'keyword4' => [
              'value' => $address['mobile']
          ],
          'keyword5' => [
              'value' => $address['province']. $address['city']. $address['country']. $address['detail']
          ]
        ];
        $this->data = $data;
    }

    /**
     * 通过用户id获取用户openid
     * @param $userID 用户id
     * @return mixed
     * @throws UserException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    private function getUserOpenID($userID){
        $user = UserModel::get($userID);
        if(!$user){
            throw new UserException();
        }
        $userOpenID = $user->openid;
        return $userOpenID;
    }
}