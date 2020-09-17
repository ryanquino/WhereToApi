<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';
    protected $fillable = [
        'restaurant_id', 'menuName','description', 'price', 'markUpPercentage','imagePath', 'categoryId', 'isFeatured'
    ];

    public function category()
    {
        return $this->hasMany('App\Categories');
    }
}
