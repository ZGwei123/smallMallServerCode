<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    /**
     * 对读取出来的url字段值改写
     * @param $value    url字段值
     * @param $data     所有字段值
     * @return string   新url字段值
     */
    public function prefixImgUrl($value, $data){
        // 图片路径是否为本地
        if($data['from'] == 1){
            return config('setting.image_prefix'). $value;
        } else {
            return $value;
        }
    }

    /**
     * 对字段值是json格式的进行解码
     * @param $value  字段值
     * @return string  json解码后的字段值
     */
    public function jsonDecode($value){
        // 该字段值为空时，将返回原值
        if(!$value){
            return $value;
        }
        return json_decode($value, true);
    }
}
