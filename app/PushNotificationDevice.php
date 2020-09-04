<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotificationDevice extends Model
{
    //
    protected $table = "notification_device";

    protected $fillable = [
        'userId', 'deviceId', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
