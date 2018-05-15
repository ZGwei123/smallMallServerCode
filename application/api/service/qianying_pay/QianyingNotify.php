<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/9
 * Time: 16:35
 */

namespace app\api\service\qianying_pay;


use app\api\model\Order as OrderModel;
use app\api\model\Product as ProductModel;
use app\api\service\Order as OrderServer;
use app\api\service\Order;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;

class QianyingNotify
{

    public function handler()
    {
        $data = Request::instance()->param();
        // 检验签名
        //$checkSign = $this->checkSign($data);
        //if($checkSign){
            // 从缓存清除限制的价格（使该价格的订单能被处理）
            cache(intval($data['m']), null);
            $orderNO = $data['oid'];
            // 开启事务，防止连续多表操作时出现数据不一致
            Db::startTrans();
            try {
                // 获取订单信息
                $orderInfo = OrderModel::where('order_no', '=', $orderNO)
                    ->find();
                // 检验支付是否成功
                $payStatus = $this->checkPayStatus($data, $orderInfo);
                if ($payStatus) {
                    // 订单状态需要为未支付，才能修改其状态
                    if ($orderInfo->status == OrderStatusEnum::UNPAID) {
                        $orderServer = new OrderServer();
                        // 检验库存
                        $stockStatus = $orderServer->checkOrderStock($orderInfo->id);
                        // 库存是否足够
                        if ($stockStatus['pass']) {
                            // 库存足够时，将修改订单状态为已支付，并减少产品库存
                            $this->updateOrderStatus($orderInfo->id, true);
                            $this->reduceProductStock($stockStatus);
                        } else {
                            // 库存不足时，由于已支付，所以订单状态修改为已支付，但库存不足
                            $this->updateOrderStatus($orderInfo->id, false);
                        }
                    }
                }
                Db::commit();
            } catch (Exception $e){
                Db::rollback();
                Log::error($e);
            }

        //}
    }

    /**
     * 检验签名
     * @param $data  请求所带参数
     * @return bool
     */
    private function checkSign($data)
    {
        $sign = md5(
            "oid=". $data['oid']. "&status=". $data['status']. "&m=". $data['m']. config('secure.key')
        );
        // 签名是否有效
        if($data['sign'] == $sign){
            return true;
        } else {
            // 初始化日志类
            Log::init([
                "type" => "File",
                "path" => LOG_PATH,
                "level" => ['error']
            ]);
            // 进行日志记录
            Log::record("签名无效", 'error');
            return false;
        }
    }

    /**
     * 检验支付状态（用户是否支付成功）
     * @param $data  请求所带参数
     * @param $orderInfo  支付的订单信息
     * @return bool
     */
    private function checkPayStatus($data, $orderInfo)
    {
        $status = $data['status'];
        $m = $data['m'];
        // 是否支付成功，且为用户所要支付的订单（才能修改支付订单的状态）
        if(($status == 1 || $status == 5 || $status == 6) && intval($m) == intval($orderInfo->total_price)){
            return true;
        }
        return false;
    }

    /**
     * 更新订单状态
     * @param $orderID 订单id
     * @param $bool true时，修改订单状态为已支付，false，则为已付款，但库存不足
     */
    private function updateOrderStatus($orderID, $bool)
    {
        // 需要修改的订单状态
        $status = $bool ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }

    /**
     * 减少产品库存
     * @param $stockStatus  库存状态（包含订单中所需产品及数量）
     * @throws Exception
     */
    private function reduceProductStock($stockStatus)
    {
        // 遍历减少所需产品的库存
        foreach($stockStatus['pStatusArray'] as $singlePStatus){
            ProductModel::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }

}