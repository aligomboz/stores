<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'price' , 'category_id' , 'img' , 'description',
    ];
    protected $appends =[
        'img_url',
    ];
    public function getImgUrlAttribute(){
        if($this->attributes['img']){
            return asset('images/' . $this->attributes['img']);
        }
    }
    protected $hidden=[
        'created_at' , 'updated_at',
    ];
    protected $table = 'products';
    public function category(){
        return $this->belongsTo(Category::class , 'category_id' , 'id');
    }
    public function imges(){
        return $this->hasMany(ProductImg::class , 'product_id' , 'id');
    }
    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
    public function tags(){
        return $this->belongsToMany(Tag::class,'products_tags' , 'product_id' , 'tag_id' , 'id' , 'id');
    }
    public function orders(){
        return $this->belongsToMany(Order::class , 'order_products')
        ->using(OrderProducts::class);
    }

    public static function bestOrderProduct($limit = 10){
        /*SELECT store_products.id, store_products.name, 
(SELECT SUM (store_order_products.quntity) FROM store_order_products
WHERE store_order_products.product_id = store_products.id) as sales 
FROM store_products
ORDER BY sales DESC
LIMIT 3; */
        return Product::select([
            'id',
            'name',
            'price',
            'img',
            DB::raw('(SELECT SUM(store_order_products.quntity) FROM store_order_products
            WHERE store_order_products.product_id = store_products.id) as sales'),
            
        ])->selectRaw('(SELECT store_categories.name FROM store_categories WHERE store_categories.id = store_products.category_id) as category_name')
          ->orderBy('sales' ,'DESC')
          ->limit($limit)
          ->get();
    }
}
