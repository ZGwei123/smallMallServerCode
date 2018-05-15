<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/1
 * Time: 18:00
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * 获取指定id的theme信息组
     * @http GET
     * @url theme?ids=id1,id2,id3...
     * @param $ids theme的id组（可以一个或多个id）
     * @return 一组theme模型
     */
    public function getThemeList($ids = ' ')
    {
        (new IDCollection())->goCheck();
        // 获取theme的id数组
        $idsArr = explode(',', $ids);
        $theme = ThemeModel::getThemeByIDs($idsArr);
        if($theme->isEmpty()){
            throw new ThemeException();
        }

//        // url参数ids中是否有主题ID在数据库中没有的
//        // 获取存在url参数ids中且数据库中也存在的ID
//        foreach($theme as $value){
//            $data = $value->getData();
//            $getIDsArr[] = $data['id'];
//        }
//        // 获取url参数ids中存在，但数据库中无的ID
//        $diffIDArr = array_diff($idsArr, $getIDsArr);
//        // 存在数据库没有的ID，则返回信息给客户端
//        if($diffIDArr){
//            throw new ThemeException([
//                'msg' => 'ID为'. implode('、', $diffIDArr). '的主题不存在，请检查主题ID'
//            ]);
//        }

        return $theme;
    }

    /**
     * 获取指定id的theme的详情信息，包括其含有的产品
     * @http GET
     * @url theme/:id
     * @param $id   theme的id
     */
    public function getThemeDetails($id){
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProduct($id);
        if(!$theme){
            throw new ThemeException();
        }
        return $theme;
    }
}