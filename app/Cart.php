<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Cart extends Pivot // قمنى بتحويله لانه جدول وسيط 
// لانحتاج الى fillable لانه معرف تلقائي 
//auto-incrementing. لانحتاج جعله فولس
{
    public $table = 'carts';
    public $timestamps = false;
    public $keyType = 'string'; //  وليس رقمstring uuid()لان 
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('id', '=', $this->attributes['id'])
                     ->where('product_id', '=', $this->attributes['product_id']);
    }

    protected function incrementOrDecrement($column, $amount, $extra, $method)
    {
        $query = $this->newQueryWithoutRelationships();

        if (! $this->exists) {
            return $query->{$method}($column, $amount, $extra);
        }

        $this->incrementOrDecrementAttributeValue($column, $amount, $extra, $method);

        $query = $this->setKeysForSaveQuery($query);
        return $query->{$method}($column, $amount, $extra);
    }
    
}
