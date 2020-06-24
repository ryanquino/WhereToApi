<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = "transactions";

    protected $fillable = [
        'clientId', 'optionalAddress', 'status'
    ];
}
