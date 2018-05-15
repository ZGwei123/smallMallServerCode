<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/10
 * Time: 19:22
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = [
        'user_id', 'delete_time', 'update_time'
    ];

    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value, $data){
        return $this->jsonDecode($value);
    }

    public function getSnapAddressAttr($value, $data){
        return $this->jsonDecode($value);
    }

    /**
     * 分页获取用户id的历史订单
     * @param $uid  用户id
     * @param int $page  当前页数
     * @param int $size  每页订单数
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getPagingOrder($uid, $page = 1, $size = 15){
        $pagingOrder = self::where('user_id', '=', $uid)
            ->order('create_time desc' )
            ->paginate($size, true, ['page' => $page]);
        return $pagingOrder;
    }

    /**
     * 分页获取所有订单
     * @param int $page 当前分页
     * @param int $size 每页数量
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getAllPaging($page = 1, $size = 20){
        $pagingOrders = self::order("create_time desc")
            ->paginate($size, true, ['page' => $page]);
        return $pagingOrders;
    }
}