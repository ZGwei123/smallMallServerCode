<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 12:17
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * @http  GET
     * @url  product/recent?count=
     * @param int $count  获取最近商品的数量
     * @return  一组最近商品信息
     */
    public function getRecentProduct($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        // 隐藏summary字段
        $products->hidden(['summary']);
        return $products;
    }

    /**
     * @http  GET
     * @url  product/by_category?id=
     * @param $id  分类id
     * @return  属于指定id分类的商品
     */
    public function getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products->hidden(['summary']);
        return $products;
    }

    /**
     * 获取指定id的详情商品
     * @http  GET
     * @url  product/:id
     * @param $id  商品id
     */
    public function getProductDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getOne($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }
}