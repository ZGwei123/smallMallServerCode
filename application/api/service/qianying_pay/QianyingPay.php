<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/8
 * Time: 21:10
 */

namespace app\api\service\qianying_pay;


use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderServer;
use app\api\service\Token;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;

class QianyingPay
{
    // 订单id
    private $orderID;
    // 订单编号
    private $orderNO;
    // 订单价格
    private $orderPrice;

    public function  __construct($orderID)
    {
        $this->orderID = $orderID;
    }

    // 支付
    public function pay()
    {
        // 检验订单
        $this->checkOrderValid();
        // 检验库存
        $orderServer =  new OrderServer();
        $status = $orderServer->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }

        // 订单的价格是否存在于缓存中（是，该价格的订单将暂时不能支付）
        $priceExist = $this->isPriceExist();
        if($priceExist['priceExist']){
            return $priceExist;
        }

        // 获取千应支付接口路径
        $url = $this->getRequestUrl($this->orderPrice);
        // 获取请求千应支付接口后响应回来的页面内容
        $responseContent = curl_get($url, $httpCode);
        // 获取二维码图片路径
        $QRCodeUrl = $this->getQRCodeUrl($responseContent);
        // 对价格进行缓存（以限制同等价格的订单进行支付）
        $this->cachePrice();
        return [
            'priceExist' => false,
            'price' => $this->orderPrice,
            'QRCodeUrl' => $QRCodeUrl
        ];
    }

    /**
     * 检验订单是否有效（订单是否存在，订单中的UID和token中的UID是否一致，订单是否已支付）
     * @return bool
     * @throws OrderException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    private function checkOrderValid(){
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
     * 订单价格是否存在于缓存中
     * @return array
     */
    private function isPriceExist(){
        $priceExist = cache(intval($this->orderPrice));
        if($priceExist){
            return [
                'priceExist' => true,
                'msg' => '价格为'. $this->orderPrice. '元的订单暂时无法支付，请稍后再支付'
            ];
        }
        return [
            'priceExist' => false
        ];
    }

    // 获取千应支付上行接口完成的url（包含请求参数）
    // orderPrice 订单总价格
    private function getRequestUrl($orderPrice)
    {
        $url = config('secure.url');
        $uid = config('secure.uid');
        $key = config('secure.key');
        $type = '101';
        // 千应支付金额只能是正整数，不能含有小数
        $m = intval($orderPrice);
        $orderid = $this->orderNO;
        $callbackurl = config('secure.callbackurl');
        $gotrue = config('secure.gotrue');
        $gofalse = config('secure.gofalse');
        $charset = 'utf-8';
        $token = '';
        $sign = 'uid='. $uid. '&type='. $type. '&m='. $m. '&orderid='. $orderid. '&callbackurl='. $callbackurl;
        $sign = md5($sign. $key);
        $url = sprintf($url, $uid, $type, $m, $orderid, $callbackurl, $sign, $gotrue, $gofalse, $charset, $token);
        return $url;

    }

    /**
     * 从请求千应支付上行接口响应返回的页面内容中获取二维码图片路径
     * @param $responseContent  请求千应支付上行接口响应返回的页面内容
     * @return string
     */
    private function getQRCodeUrl($responseContent)
    {
        // 获取下标
        $QRCodeIndex = strpos($responseContent, "二维码");
        // 截取一段包含二维码图片路径的字符串
        $containQRCodeUrl = substr($responseContent, $QRCodeIndex - 50, 50);
        // 匹配找到二维码图片路径字符串
        preg_match_all("/src=[\'\"]([\s\S]*)[\'\"]/U", $containQRCodeUrl, $arr);
        // 二维码图片路径是否获取成功
        if(!isset($arr[1][0])){
            throw new Exception("获取二维码图片路径失败：". $responseContent);
        }
        // 获取完整二维码图片路径
        $QRCodeUrl = sprintf(config('secure.QRCodeBaseUrl'), $arr[1][0]);
        return $QRCodeUrl;
    }

    /**
     * 对价格进行缓存（以限制同等价格的订单进行支付）
     * @throws Exception
     */
    private function cachePrice(){
        $result = cache(intval($this->orderPrice), true, config('secure.price_expire_in'));
        if(!$result){
            throw new Exception('缓存出现异常');
        }
    }
}