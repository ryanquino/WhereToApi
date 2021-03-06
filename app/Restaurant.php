<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    //
	protected $fillable = [
        'restaurantName', 'owner','representative', 'latitude','longitude', 'barangayId','cityId','contactNumber', 'openTime', 'closingTime', 'closeOn', 'isFeatured', 'status', 'imagePath'
    ];

    public function menu()
    {
        return $this->hasMany('App\Menu');
    }
}
