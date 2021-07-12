<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//coupons
Route::get('coupons','Api\CouponsApiController@index');
Route::get('coupons/{id}','Api\CouponsApiController@details');
Route::post('coupons','Api\CouponsApiController@create');
Route::put('coupons/{id}','Api\CouponsApiController@update');
Route::delete('coupon/{id}','Api\CouponsApiController@delete');

//shops
Route::get('shops','Api\ShopsApiController@index');
Route::get('shops/{id}','Api\ShopsApiController@details');
Route::post('shops','Api\ShopsApiController@create');
Route::put('shops/{id}','Api\ShopsApiController@update');
Route::delete('shop/{id}','Api\ShopsApiController@delete');

//couponShops

Route::get('coupons/{coupondId}/shops','Api\CouponShopsApiController@couponShops');
Route::get('coupons/{coupondId}/shops/{shop_id}','Api\CouponShopsApiController@details');
Route::post('coupons/{coupondId}/shops','Api\CouponShopsApiController@create');
Route::delete('coupons/{coupondId}/shops/{shop_id}','Api\CouponShopsApiController@delete');
