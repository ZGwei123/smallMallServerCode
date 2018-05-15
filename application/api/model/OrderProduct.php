<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/10
 * Time: 19:31
 */

namespace app\api\model;


class OrderProduct extends BaseModel
{
    /**
     * 用订单ID获取订单中的商品列表信息，包括商品ID及该商品的下单数量
     * @param $orderID  订单ID
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOrderProduct($orderID){
        $orderProducts = self::where('order_id', '=', $orderID)
            ->select();
        return $orderProducts;
    }
}