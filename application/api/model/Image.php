<?php

namespace app\api\model;

use think\Model;

class Image extends BaseModel
{
    protected $hidden = ['id', 'update_time', 'delete_time', 'from'];

    /**
     * 读取器
     * @param $value    url字段值
     * @param $data     所有字段值
     * @return string   新url字段值
     */
    public function getUrlAttr($value, $data){
        return $this->prefixImgUrl($value, $data);
    }
}
