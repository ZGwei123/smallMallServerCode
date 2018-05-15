<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 17:35
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /**
     * @http  GET
     * @url  category/all
     * @return  所有分类
     */
    public function getAllCategory(){
        $categories = CategoryModel::getAll();
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}