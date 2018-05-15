<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/1
 * Time: 18:02
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = [
        'create_time', 'update_time', 'delete_time', 'from',
        'category_id', 'pivot'
    ];

    // 读取器
    public function getMainImgUrlAttr($value, $data){
        return $this->prefixImgUrl($value, $data);
    }

    // 关联模型
    public function imgs(){
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public function properties(){
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    /**
     * @param $count  获取最近商品的数量
     * @return 一组最近商品的信息
     */
    public static function getMostRecent($count){
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    /**
     * @param $id  分类id
     * @return  属于指定id分类的商品
     */
    public static function getProductsByCategoryID($id){
        $products = self::where('category_id', '=', $id)
            ->select();
        return $products;
    }

    /**
     * 获取指定id的详情商品
     * @param $id  商品id
     */
    public static function getOne($id){
        $product = self::with([
            // 利用闭包函数构建查询器，对图片进行排序
                'imgs' => function($query){
                    $query->with(['imgUrl'])
                    ->order('order', 'asc');
                }
            ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }
}