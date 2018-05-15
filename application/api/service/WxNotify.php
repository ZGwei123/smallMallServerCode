<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/17
 * Time: 9:41
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    /**
     * 重写WxPayNotify类的NotifyProcess方法，该方法用于处理微信支付后异步回调传入的支付状态信息，
     * 微信每隔一段时间会自动调用
     * @param array $data  支付状态信息
     * @param string $msg
     * @return bool|\true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     * @throws \app\lib\exception\OrderException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function NotifyProcess($data, &$msg)
    {
        // 支付成功，就对订单状态等进行处理
        if($data['result_code'] == 'SUCCESS'){
            // 获取订单编号
            $orderNo = $data['out_trade_no'];
            // 开启事务，利用事务的锁机制防止对数据库的多次操作（进行多次减库存）
            Db::startTrans();
            try{
                // 订单id
                $order = OrderModel::where('order_no', '=', $orderNo)
                    ->find();
                // 订单为待支付时才需要处理
                if($order->status == OrderStatusEnum::UNPAID){
                    // 重新检测库存
                    $orderServer = new Order();
                    $stockStatus = $orderServer->checkOrderStock($order->id);
                    // 产品库存是否足够
                    if($stockStatus['pass']){
                        // 足够时，修改订单状态为已支付
                        $this->updateOrderStatus($order->id, true);
                        // 减少库存
                        $this->reduceStock($stockStatus);
                    } else {
                        // 不足时，修改订单状态为已支付但库存不足
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();
            } catch (Exception $e){
                Db::rollback();
                // 出现异常，无法正常处理时，返回false给微信，让微信重新调用该接口
                Log::error($e);
                return false;
            }
        }
        // 微信异步回调通知支付状态，不论支付状态是成功还是失败，都要返回true，通知微信服务端接口已正常处理，
        // 以取消微信的间隔调用通知
        return true;
    }

    /**
     * 更新订单状态
     * @param $orderID  订单id
     * @param $bool  true或false
     */
    private function updateOrderStatus($orderID, $bool){
        // 根据$bool参数值，修改数据库中订单的状态为已支付或已支付但库存不足
        $status = $bool ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }

    /**
     * 减少产品库存
     * @param $stockStatus  库存状态信息（包含订单中的产品信息）
     * @throws \think\Exception
     */
    private function reduceStock($stockStatus){
        // 遍历订单中的每件产品，以对每件产品减少库存量
        foreach($stockStatus['pStatusArray'] as $singlePStatus){
            Product::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }
}