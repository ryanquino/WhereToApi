<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    //
	protected $fillable = [
        'restaurantName', 'address', 'barangayId','contactNumber', 'openTime', 'closingTime', 'closeOn', 'isFeatured', 'status','imagePath'
    ];

    public function menu()
    {
        return $this->hasMany('App\Menu');
    }
}
