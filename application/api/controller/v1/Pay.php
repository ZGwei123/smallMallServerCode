<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/11
 * Time: 21:59
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\qianying_pay\QianyingNotify;
use app\api\service\qianying_pay\QianyingPay as QianyingPayServer;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder,payment']
    ];

    /**
     * 有前端调用支付接口，此时服务器调用微信统一下单接口获取预支付参数，返回签名等参数给客户端
     * 让客户端根据参数调起微信支付接口
     * @http  POST
     * @url  /pay/pre_order             id=
     * @param string $id  订单主键id
     * @throws \app\lib\exception\ParameterException
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        // 签名等参数信息
        $sign = $pay->pay();
        return $sign;
    }

    /**
     * 微信异步调用该方法（通知服务端支付结果）
     * @http  POST
     * @url  /pay/notify
     */
    public function processNotify(){
        $notify = new WxNotify();
        $notify->Handle();
    }


    /**
     * 前端调用支付（用千应网络的支付接口url来获取二维码实现扫码支付）
     * @http POST
     * @url pay/payment
     * @param int $id  订单id
     * @return mixed|string
     */
    public function payment($id = 1){
        (new IDMustBePositiveInt())->goCheck();
        $qianyingPay = new QianyingPayServer($id);
        // 二维码路径和价格（在库存不足时，该变量值是订单状态；在订单价格存在于缓存中时，该变量值是订单暂时不能被支付的信息）
        $QRCodeUrl = $qianyingPay->pay();
        return $QRCodeUrl;
    }

    /**
     * 用于千应支付进行支付结果的异步回调（通知服务端支付结果）
     * @http GET
     * @url pay/process_notify
     */
    public function notify(){
       $qianyingNotify = new QianyingNotify();
       $qianyingNotify->handler();
    }

}