<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    //
    protected $table = 'delivery_addresses';
    protected $fillable = [
        'userId', 'addressName', 'latitude', 'longitude'
    ];
}
