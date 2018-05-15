<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/9
 * Time: 19:29
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order
{
    // 订单里的商品列表
    protected $oProducts;
    // 订单信息对应的真实的商品信息列表
    protected $products;
    // 订单对应的用户的uid
    protected $uid;

    public function place($uid, $oProducts)
    {
        $this->uid = $uid;
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }
        // 创建订单
        $orderSnap = $this->snapOrder($status);
        // 保存订单
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    /**
     * 根据订单里的商品列表信息查找对应的真实商品信息
     * @param $oProducts  订单商品列表数组
     */
    private function getProductsByOrder($oProducts)
    {
        // 获取订单里的商品ID
        $oPIDs = [];
        foreach($oProducts as $oProduct){
            array_push($oPIDs, $oProduct['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $products;
    }

    // 获取订单状态（包含订单库存够不够，订单总额，订单商品数量总和，订单中每个商品状态）
    private function getOrderStatus()
    {
        // 初始订单状态，通过所有商品的状态改写订单状态
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        // 获取每个订单商品的状态
        foreach($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    /**
     * 获取每个订单商品状态（包含商品id，商品库存够不够，商品请求数量，商品名称，商品总额）
     * @param $oPID  订单商品ID
     * @param $oCount  订单商品数量
     * @param $products  订单商品列表信息对应的真实（数据库里）的商品列表数量
     * @return array
     * @throws OrderException
     * @throws \think\Exception
     */
    private function getProductStatus($oPID, $oCount, $products)
    {
        // 订单商品初始状态，通过对比订单商品和真实商品来改写商品状态
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0,
            'price' => 0,
            'main_img_url' => null
        ];
        // 查找出订单商品在真实商品列表的下标位置
        $pIndex = -1;
        for($i = 0; $i < count($products); $i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        if($pIndex == -1){
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        } else {
            // 改写订单商品状态
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['count'] = $oCount;
            $pStatus['name'] = $product['name'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            if($product['stock'] - $oCount >= 0){
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    /**
     * 生成订单快照
     * @param $status  订单状态
     * @throws UserException
     */
    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = json_encode($status['pStatusArray']);
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if(count($this->products) > 1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    // 获取下单用户的收货地址
    private function getUserAddress(){
        $address = UserAddress::getAddress($this->uid);
        if(!$address){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败'
            ]);
        }
        return $address;
    }

    /**
     * 将订单保存到数据库
     * @param $snap  订单快照
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    private function createOrder($snap){
        Db::startTrans();
        try {
            // 将订单数据保存到order订单表
            $orderNo = self::makeOrderNo();
            $order = new OrderModel();
            $order->order_no = $orderNo;
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = $snap['pStatus'];
            $order->save();
            // 获取订单id及创建时间
            $orderID = $order->id;
            $create_time = $order->create_time;

            // 将订单id添加到用户提交的商品列表中的每个商品中
            foreach ($this->oProducts as &$oProduct) {
                $oProduct['order_id'] = $orderID;
            }

            // 将订单和产品的关联信息添加到order_product表中
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            // 返回订单id号、订单编号、订单创建时间
            Db::commit();
            return [
                'order_id' => $orderID,
                'order_no' => $orderNo,
                'create_time' => $create_time
            ];
        } catch(Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    // 生成订单号
    public static function makeOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderNo = $yCode[intval(date('Y') - 2018)]. strtoupper(dechex(date('m'))).
            date('d'). substr(time(), -5). substr(microtime(), 2, 5).
            sprintf('%02d', rand(0, 99));
        return $orderNo;
    }

    /**
     * 根据订单ID，检查订单库存状态（可由外部创建对象来调用该方法去重新检查订单库存）
     * @param $orderID  订单ID
     * @return array
     * @throws Exception
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkOrderStock($orderID){
        // 通过订单ID，从order_product表中获取订单的商品列表信息
        $orderProducts = OrderProduct::getOrderProduct($orderID);
        // 调用getProductsByOrder(),传入订单商品列表参数获取对应的真实的商品列表信息
        $originalProducts = $this->getProductsByOrder($orderProducts);

        $this->oProducts = $orderProducts;
        $this->products = $originalProducts;

        // 用订单商品列表及对应的真实商品列表即可通过getOrderStatus()方法获取订单状态
        $status = $this->getOrderStatus();
        return $status;
    }

    /**
     * 修改订单状态为收货，并发送模板消息
     * @param $orderID 订单id
     * @param string $jumpPage 用点击模板后跳转小程序指定页面
     * @return bool
     * @throws Exception
     * @throws OrderException
     * @throws \think\exception\DbException
     */
    public function delivery($orderID, $jumpPage = '')
    {
        $order = OrderModel::get($orderID);
        if(!$order){
            // 订单不存在
            throw new OrderException();
        }
        if($order->status != OrderStatusEnum::PAID){
            // 订单不是付款状态
            throw new OrderException([
                'msg' => '订单已更新或还未付款',
                'errrorCode' => 70002,
                'code' => 403
            ]);
        }
        // 修改订单状态为收货
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();
        // 发送模板消息
        $deliveryMessage = new DeliveryMessage();
        return $deliveryMessage->sendDeliveryMessage($order, $jumpPage);
    }

    /**
     * 一般订单（非微信订单）则只需修改订单状态为收货即可
     * @param $orderID 订单id
     * @return bool
     * @throws Exception
     * @throws OrderException
     * @throws \think\exception\DbException
     */
    public function processDelivery($orderID)
    {
        $order = OrderModel::get($orderID);
        if(!$order){
            // 订单不存在
            throw new OrderException();
        }
        if($order->status != OrderStatusEnum::PAID){
            // 订单不是付款状态
            throw new OrderException([
                'msg' => '订单已更新或还未付款',
                'errrorCode' => 70002,
                'code' => 403
            ]);
        }
        // 修改订单状态为收货
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();
        return true;
    }
}