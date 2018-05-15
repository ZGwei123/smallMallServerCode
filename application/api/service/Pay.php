<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/11
 * Time: 22:13
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    // 订单ID
    private $orderID;
    // 订单编号
    private $orderNO;
    // 订单价格
    private $orderPrice;

    public function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    /**
     * 进行支付（前端调用支付接口，服务器向微信发送预订单获取签名返回给前端，前端再用签名调起微信支付进行支付）
     * @return array|null
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pay()
    {
        // 检查订单
        $this->checkOrderValid();
        $orderService = new Order();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']) {
            return $status;
        }
        // 签名，（微信统一下单接口，返回预支付信息，返回客户端签名等参数信息）
        $wxPreOrder = $this->makeWxPreOrder($this->orderPrice);
        return $wxPreOrder;
    }

    /**
     * 检查订单是否可以被操作（订单ID是否存在，当前token中的UID是否和订单UID一致，订单是否已支付）
     * @return bool
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \think\exception\DbException
     */
    private function checkOrderValid()
    {
        $order = OrderModel::get($this->orderID);

        if(!$order){
            throw new OrderException();
        }

        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'code' => 400,
                'msg' => '订单已支付',
                'errorCode' => 70003
            ]);
        }

        // 获取订单编号
        $this->orderNO = $order->order_no;
        // 获取订单价格
        $this->orderPrice = $order->total_price;
        return true;
    }

    /**
     * 调用微信支付SDK，生成微信预订单
     * @param $totalPrice  订单总额
     * @throws Exception
     * @throws TokenException
     */
    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentUid();
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('wx.notify_url'));
        $paySignature = $this->getPaySignature($wxOrderData);
        return $paySignature;
    }

    /**
     * 调用微信支付SDK发送预订单（微信统一下单接口）获取预支付信息
     * @param $wxOrderData  微信预订单
     * @throws \WxPayException
     */
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
            throw new Exception("向微信微信发起统一支付接口失败！");
        }
        // 记录prepay_id（该字段值用调用微信模板向用户发送消息）
        $this->recordPreOrder($wxOrder);
        // 获取签名等参数数组
        $sign = $this->sign($wxOrder);
        return $sign;
    }

    /**
     * 记录微信返回结果中的prepay_id，存在订单表中，用于调用微信模板发送消息给用户
     * @param $wxOrder  微信返回的结果状态
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', '=', $this->orderID)
            ->upadte(['prepay_id' => $wxOrder['prepay_id']]);
    }

    /**
     * 调用微信SDK生成签名参数，并生成将返回给客户端调起微信支付的参数数组
     * @param $wxOrder  微信返回的预支付信息
     * @return array
     */
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        // 传入小程序appId
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        // 生成随机数
        $rand = md5(time(). mt_rand(0, 1000));
        $jsApiPayData->SetPackage('prepay_id='. $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        // 生成签名
        $sign = $jsApiPayData->MakeSign();
        // 参数数组
        $rawValues = $jsApiPayData->GetValues();
        // 参数数组中添加签名参数
        $rawValues['paySign'] = $sign;
        // 参数数组中去除appId参数，该参数在上面传入只是为生成签名
        unset($rawValues['appId']);

        return $rawValues;
    }
}