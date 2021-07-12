<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Coupon;
use App\CouponShops;
use Validator;
use App\Http\Controllers\ApiController;
use App\Shop;
use Illuminate\Http\Response;
use stdClass;
class CouponShopsApiController extends ApiController
{
   
    public function couponShops(Request $request,$id) {
        $error = new stdClass();
        $begin = microtime(true);
        if($request->limit || $request->offset) {
            $coupons = Coupon::with('shops')
                                    ->take($request->limit)->skip($request->offset)
                                    ->get();

        }else {
        $coupon_shops = Coupon::with('shops')->get();
        }
        $duration =   microtime(true) - $begin;
        
        return $this->respondListCollection(1,$coupon_shops,$error,$duration,$request->limit,$request->offset);
    }

    public function details($coupon_id,$shop_id) {
        $begin = microtime(true);

        $error = new stdClass();
        $coupon = Coupon::where('id',$coupon_id)
                        ->first();
        $shop = Shop::where('id',$shop_id)->first();
        $coupon->shops = $shop;
       
        $duration =  microtime(true) - $begin;
       

        if(is_null($coupon)) {
          
            return $this->respondErrorListCollection(0,[],$this->error('coupon not found',404),round($duration,3));

        }
        return $this->respondDetail(1,$coupon,$error,$duration);

    }

    public function delete($coupon_id,$shop_id) {
        $begin = microtime(true);
        $error = new stdClass();
        $duration =  microtime(true) - $begin;
        $coupon_shops = CouponShops::where('coupon_id',$coupon_id)->where('shop_id',$shop_id)->first();
        if(is_null($coupon_shops)) {
            return $this->respondErrorListCollection(0,[],$this->error('coupon not found',404),round($duration,3));
        }else{
            $coupon_shops->delete();
            return $this->respondSuccessCollection(1,Response::HTTP_OK,$this->putData('deleted',$coupon_shops->id),$error,round($duration,3));

        }
    }

    public function create(Request $request) {
        $error = new stdClass();
        $data = $request->all();
        $begin = microtime(true);
        $duration =  microtime(true) - $begin;

        $validatedData = \Validator::make($data, [
           
            'coupon_id' => 'required|integer',
            'shop_id' => 'required|integer',
            
        ]);
        if ($validatedData->fails()) {
       
            return $this->respondErrorValidation('0', [], $validatedData->errors(), round($duration,3));
        }
        $coupon_shops = $this->checkAndStore($data);
        if($coupon_shops == 'existed') {
            return $this->respondExistedCollection(0,Response::HTTP_CONFLICT,[],$this->error('The inserting resource was already registered.',Response::HTTP_CONFLICT),round($duration,3));

        }else {
            return $this->respondSuccessCollection(1,Response::HTTP_CREATED,$this->putData('id',$coupon_shops->id),$error,round($duration,3));

        }
       
    }

    public function checkAndStore($data) {
        $coupon_shops = CouponShops::where('coupon_id',$data['coupon_id'])
        ->where('shop_id',$data['shop_id'])->first();
        if(is_null($coupon_shops)){
            $coupon_shop = new CouponShops();
            $coupon_shop->coupon_id = $data['coupon_id'];
            $coupon_shop->shop_id = $data['shop_id'];
            $coupon_shop->save();
            return $coupon_shop;
        }else{
            return 'existed';
        }
       
    }
}
