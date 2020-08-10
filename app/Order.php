<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = "transactions";

    protected $fillable = [
        'clientId', 'riderId', 'restaurantId', 'deliveryAddress', 'barangayId', 'deliveryCharge','status'
    ];
}
