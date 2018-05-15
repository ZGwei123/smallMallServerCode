<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get("api/:version/banner/:id", "api/:version.Banner/getBanner");

Route::get('api/:version/theme', 'api/:version.Theme/getThemeList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getThemeDetails');

Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id', 'api/:version.Product/getProductDetail', [], ['id' => '\d+']);
Route::get('api/:version/product/recent', 'api/:version.Product/getRecentProduct');

/*Route::group('api/:version/product', function (){
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getProductDetail', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecentProduct');
});*/

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategory');


Route::post('api/:version/token/user', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');
Route::post('api/:version/token/app', 'api/:version.Token/loginGetToken');


Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address', 'api/:version.Address/getAddress');


Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');

Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');
Route::put('api/:version/order/process_delivery', 'api/:version.Order/processDelivery');


Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify', 'api/:version.Pay/processNotify');
Route::post('api/:version/pay/payment', 'api/:version.Pay/payment');
Route::get('api/:version/pay/process_notify', 'api/:version.Pay/notify');