<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    //
    use SoftDeletes;
    protected $table = 'shops';
    protected $hidden = ['pivot'];

    protected $fillable = [
        'admin_id',
        'name',
        'query',
        'latitude',
        'longitude',
        'zoom'
    ];
}
