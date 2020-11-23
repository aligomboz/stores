<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    protected $fillable = [
        'imgPath' , 'product_id',
    ];
    protected $appends =[
        'img_url',
    ];
    public function getImgUrlAttribute(){
        if($this->attributes['imgPath']){
            return asset('images/' . $this->attributes['imgPath']);
        }
    }
    public function product(){
        return $this->belongsTo(Product::class , 'product_id' , 'id');
    }
}
