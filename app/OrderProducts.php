<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProducts extends Pivot
{
    //
    public $timestamps = false;

    protected $guarded = [];
    public $table = 'order_products';
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
