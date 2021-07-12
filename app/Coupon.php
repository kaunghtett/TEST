<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    //
    use SoftDeletes;

    protected $table = 'coupons';
    protected $hidden = ['pivot'];


    protected $fillable = [ 
        'admin_id',
        'name',
        'description',
        'discount_type',
        'amount',
        'image_url',
        'code',
        'start_datetime',
        'end_datetime',
        'coupon_type',
        'used_count'
    ];

    public function shops() {
        return $this->belongsToMany(Shop::class,'coupon_shops');
    }
}
