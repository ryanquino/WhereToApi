<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','contactNumber', 'latitude','longitude', 'cityId', 'deliveryAddress','password', 'status', 'userType','barangayId', 'imagePath'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [];
    }

    public function playerId()
    {
        return $this->hasOne('App\PushNotificationDevice');
    }
    public function verification(){
         return $this->hasOne('App\Verification', 'userId');
    }

    public function remittance()
    {
        return $this->hasOne('App\Remittance', 'userId');
    }
    public function deliveryAddress()
    {
        return $this->hasMany('App\Models\DeliveryAddress', 'userId');
    }
}
