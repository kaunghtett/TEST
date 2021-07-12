<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponShops extends Model
{
    //
    protected $table = "coupon_shops";

    protected $fillable = [
        'coupon_id',
        'shop_id'
    ];


}
