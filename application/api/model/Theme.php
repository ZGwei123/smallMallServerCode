<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/1
 * Time: 18:01
 */

namespace app\api\model;


class Theme extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'topic_img_id', 'head_img_id'];

    // 关联模型
    public function topicImg(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg(){
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function products(){
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    /**
     * 通过$ids获取一组theme信息
     * @param $idArr theme主键id的数组
     */
    public static function getThemeByIDs($idArr){
        $theme = self::with(['topicImg', 'headImg'])->select($idArr);
        return $theme;
    }

    /**
     * @param $id   theme主键id
     * @return  指定id的theme的详情信息
     */
    public static function getThemeWithProduct($id){
        $theme = self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}