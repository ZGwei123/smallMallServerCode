<?php

namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id', 'update_time', 'delete_time', 'img_id', 'banner_id'];
    /**
     * 关联模型（关联Image）
     */
    public function img(){
        return $this->belongsTo("image", "img_id", "id");
    }
}
