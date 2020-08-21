<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    //

    protected $table = 'verification';

    protected $fillable = [
        'userId', 'imagePath', 'isVerified', 'isSuspended'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'userId');
    }
}
