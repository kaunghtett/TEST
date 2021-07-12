<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Coupon;
use Validator;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Response;
use stdClass;
class CouponsApiController extends ApiController
{
   
    public function index(Request $request) {
        $error = new stdClass();
        $begin = microtime(true);
        if($request->limit || $request->offset) {
            $coupons = Coupon::take($request->limit)->skip($request->offset)->get();

        }else {
            $coupons = Coupon::get();
        }
        $duration =   microtime(true) - $begin;
        
        return $this->respondListCollection(1,$coupons,$error,$duration,$request->limit,$request->offset);
    }

    public function details($id) {
        $begin = microtime(true);

        $error = new stdClass();
        $coupon = Coupon::where('id',$id)->latest()->first();
        $duration =  microtime(true) - $begin;
       

        if(is_null($coupon)) {
          
            return $this->respondErrorListCollection(0,[],$this->error('coupon not found',404),round($duration,3));

        }
        return $this->respondDetail(1,$coupon,$error,$duration);

    }

    public function delete($id) {
        $begin = microtime(true);
        $error = new stdClass();
        $duration =  microtime(true) - $begin;
        $coupon = Coupon::where('id',$id)->first();
        if(is_null($coupon)) {
            return $this->respondErrorListCollection(0,[],$this->error('coupon not found',404),round($duration,3));
        }else{
            $coupon->delete();
            return $this->respondSuccessCollection(1,Response::HTTP_OK,$this->putData('deleted',$coupon->id),$error,round($duration,3));

        }
    }

    public function create(Request $request) {
        $error = new stdClass();
        $data = $request->all();
        $begin = microtime(true);
        $duration =  microtime(true) - $begin;

        $validatedData = \Validator::make($data, [
            'name' => 'required|max:128',
            'discount_type' => 'required|in:percentage,fix-amount',
            'amount' => 'required|integer',
            'code' => 'required|integer',
            'start_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'end_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'coupon_type' => 'required|in:private,public',
            'used_count' => 'required'
        ]);
        if ($validatedData->fails()) {
       
            return $this->respondErrorValidation('0', [], $validatedData->errors(), round($duration,3));
        }
        $coupon = $this->store($data);

        return $this->respondSuccessCollection(1,Response::HTTP_CREATED,$this->putData('id',$coupon->id),$error,round($duration,3));
       
    }

    public function update(Request $request,$id) {
        
        $error = new stdClass();
        $data = $request->all();
        $begin = microtime(true);
        $duration =  microtime(true) - $begin;
        $coupon = Coupon::where('id',$id)->first();

        //validate
        
        $validatedData = \Validator::make($data, [
            'name' => 'required|max:128',
            'discount_type' => 'required|in:percentage,fix-amount',
            'amount' => 'required|integer',
            'code' => 'required|integer',
            'start_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'end_datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'coupon_type' => 'required|in:private,public',
            'used_count' => 'required'
        ]);
        if ($validatedData->fails()) {
       
            return $this->respondErrorValidation('0', [], $validatedData->errors(), round($duration,3));
        }


        if(is_null($coupon)) {
            return $this->respondErrorValidation(0,[],$this->error('coupo not found',404),round($duration,3));
        }else{
            $coupon->admin_id = $data['admin_id'];
            $coupon->name = $data['name'];
            $coupon->description = $data['description'];
            $coupon->discount_type = $data['discount_type'];
            $coupon->amount = $data['amount'];
            $coupon->image_url = $data['image_url'];
            $coupon->code = $data['code'];
            $coupon->start_datetime = $data['start_datetime'];
            $coupon->end_datetime = $data['end_datetime'];
            $coupon->coupon_type = $data['coupon_type'];
            $coupon->used_count = $data['used_count'];
            $coupon->update();
        }
       
        
        return $this->respondSuccessCollection(1,Response::HTTP_OK,$this->putData('updated',$coupon->id),$error,round($duration,3));

    }


    public function store($data) {
        $coupon = new Coupon();
        $coupon->admin_id = $data['admin_id'];
        $coupon->name = $data['name'];
        $coupon->description = $data['description'];
        $coupon->discount_type = $data['discount_type'];
        $coupon->amount = $data['amount'];
        $coupon->image_url = $data['image_url'];
        $coupon->code = $data['code'];
        $coupon->start_datetime = $data['start_datetime'];
        $coupon->end_datetime = $data['end_datetime'];
        $coupon->coupon_type = $data['coupon_type'];
        $coupon->used_count = $data['used_count'];
        $coupon->save();
        return $coupon;
    }
}
