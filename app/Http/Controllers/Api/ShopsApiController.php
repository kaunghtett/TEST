<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Shop;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Response;
use stdClass;

class ShopsApiController extends ApiController
{
    //
    public function index(Request $request) {
        $error = new stdClass();
        $begin = microtime(true);
        if($request->limit || $request->offset) {
            $shops = Shop::take($request->limit)->skip($request->offset)->get();
            
        }else {
            $shops = Shop::get();
        }
        $duration = microtime(true) - $begin;
        
        return $this->respondListCollection(1,$shops,$error,$duration,$request->limit,$request->offset);
    }
    
    public function details($id) {
        $begin = microtime(true);
        
        $error = new stdClass();
        $shop = Shop::where('id',$id)->latest()->first();
        $duration =  microtime(true) - $begin;
        
        
        if(is_null($shop)) {
            
            return $this->respondErrorListCollection(0,[],$this->error('shop not found',404),$duration);
            
        }
        return $this->respondDetail(1,$shop,$error,$duration);
        
    }
    
    public function delete($id) {
        $begin = microtime(true);
        $error = new stdClass();
        
        $shop = Shop::where('id',$id)->first();
        if(is_null($shop)) {
            $duration =  microtime(true) - $begin;
            return $this->respondErrorListCollection(0,[],$this->error('coupon not found',404),round($duration,3));
        }else{
            $duration =  microtime(true) - $begin;
            $shop->delete();
            return $this->respondSuccessCollection(1,Response::HTTP_OK,$this->putData('deleted',$shop->id),$error,round($duration,3));
            
        }
    }
    
    public function create(Request $request) {
        $error = new stdClass();
        $data = $request->all();
        $begin = microtime(true);
        
        $validatedData = \Validator::make($data, [
            'name' => 'required|max:64',
            'query' => 'max:64',
            'latitude' => "required|regex:/^\d*(\.\d{1,2})?$/",
            'longitude' => "required|regex:/^\d*(\.\d{1,2})?$/",
            'zoom' => 'integer',
            
            ]);
            if ($validatedData->fails()) {
                $duration =  microtime(true) - $begin;
                
                return $this->respondErrorValidation('0', [], $validatedData->errors(), round($duration,3));
            }
            $shop = $this->store($data);
            $duration =  microtime(true) - $begin;
            
            
            return $this->respondSuccessCollection(1,Response::HTTP_CREATED,$this->putData('id',$shop->id),$error,round($duration,3));
            
        }
        
        public function update(Request $request,$id) {
            
            $error = new stdClass();
            $data = $request->all();
            $begin = microtime(true);
            $duration =  microtime(true) - $begin;
            $shop = Shop::where('id',$id)->first();
            
            //validate
            
            $validatedData = \Validator::make($data, [
                'name' => 'required|max:64',
                'query' => 'max:64',
                'latitude' => "required|regex:/^\d*(\.\d{1,2})?$/",
                'longitude' => "required|regex:/^\d*(\.\d{1,2})?$/",
                'zoom' => 'integer',
                ]);
                if ($validatedData->fails()) {
                    
                    return $this->respondErrorValidation('0', [], $validatedData->errors(), round($duration,3));
                }
                
                
                if(is_null($shop)) {
                    return $this->respondErrorListCollection(0,[],$this->error('coupo not found',404),round($duration,3));
                }else{
                    
                    $shop->admin_id = $data['admin_id'];
                    $shop->name = $data['name'];
                    $shop->query = $data['query'];
                    $shop->latitude = $data['latitude'];
                    $shop->longitude = $data['longitude'];
                    $shop->zoom = $data['zoom'];
                    $shop->update();
                }
                
                return $this->respondSuccessCollection(1,Response::HTTP_OK,$this->putData('updated',$shop->id),$error,round($duration,3));
            }
            
            
            public function store($data) {
                $shop = new Shop();
                $shop->admin_id = $data['admin_id'];
                $shop->name = $data['name'];
                $shop->query = $data['query'];
                $shop->latitude = $data['latitude'];
                $shop->longitude = $data['longitude'];
                $shop->zoom = $data['zoom'];
                $shop->save();
                return $shop;
            }
            
        }
        