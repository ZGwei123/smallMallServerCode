<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/8
 * Time: 22:57
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser,getDetail'],
        'checkSuperScope' => ['only' => 'getSummary,delivery,processDelivery']
    ];

    /**
     * 简洁分页（只获取当前页记录，不会去查询总记录数）获取用户历史订单
     * @http  GET
     * @url  /order/by_user?page=*&size=*
     * @param int $page  当前页数
     * @param int $size  每页记录数
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function getSummaryByUser($page = 1, $size = 15){
        (new PagingParameter())->goCheck();
        // 获取用户id
        $uid = TokenService::getCurrentUid();
        $pagingOrder = OrderModel::getPagingOrder($uid, $page, $size);
        // 当该分页订单为空时，返回空数据给客户端，表示该分页无订单记录
        if($pagingOrder->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrder->currentPage()
            ];
        }

        $data = $pagingOrder->hidden(['snap_items', 'snap_address', 'prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrder->currentPage()
        ];
    }

    /**
     * 获取单个订单的详情信息
     * @http GET
     * @url  order/:id
     * @param $id  订单id
     * @return static
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getDetail($id = 1){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    /**
     * 下单
     * @http  POST
     * @url  /order
     * @return array
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $oProducts = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $oProducts);
        return $status;
    }

    /**
     * 分页获取所有订单
     * @param int $page 当前页数
     * @param int $size 每页数量
     */
    public function getSummary($page = 1, $size = 20)
    {
        (new PagingParameter())->goCheck();
        $pagingOrders = OrderModel::getAllPaging($page, $size);
        if($pagingOrders->isEmpty()){
            // 该分页无订单时，返回一个空数据即可
            return [
                "current_page" => $pagingOrders->currentPage(),
                "data" => [],
            ];
        }
        $data = $pagingOrders->hidden(['snap_item', 'snap_address'])->toArray();
        return [
            "current_page" => $pagingOrders->currentPage(),
            "data" => $data
        ];
    }

    /**
     * 发货(微信订单)，修改订单状态，并发送模板消息
     * @http PUT
     * @url order/delivery      id=
     * @param $id 订单id
     */
    public function delivery($id = 1)
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }

    /**
     * 发货(非微信订单),修改订单状态
     * @param int $id
     * @return SuccessMessage
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function processDelivery($id = 1)
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->processDelivery($id);
        if($success){
            return new SuccessMessage();
        }
    }

}