<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/25
 * Time: 15:31
 */

namespace app\api\controller\v2;


use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use think\Exception;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @param $id   banner的id号
     */
    public function getBanner($id){
        return 'This is V1 version';
    }
}