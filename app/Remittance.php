<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    //
    protected $table = 'remittance';

    protected $fillable = [
        'riderId', 'amount','imagePath', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','userId');
    }
}
