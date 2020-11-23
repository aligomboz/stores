<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $guarded = [];

    public function products(){
        return $this->belongsToMany(Product::class , 'order_products')
        ->using(OrderProducts::class)
        ->withPivot([
            'price' , 'quntity'
        ])->as('details');//pivotلوبدي اغير اسم 
    }

    public function orderProducts(){
        return $this->hasMany(OrderProducts::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
