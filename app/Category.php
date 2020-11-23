<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Category extends Model
{
    
    protected $fillable =[
        'name' , 'parent_id', 'status' , 'careted_at' , 'update_at'
    ];
    protected $table = 'categories';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATE_AT = 'update_at';

    public static function getValidator($data , $except = 0){
        $rols = [
            'name' =>'required|string|max:255|min:3|unique:categories,name,'.$except,
            'parent_id' => 'nullable|int|exists:categories,id',
            'status' => 'required|in:published,draft'];
            
        $validator = Validator::make($data ,$rols,[
            'required' =>':attribute require data',
            'min'=>':attribute يجب الايقل عن 3'
        ]);
        return $validator;
       
    }

    public function products(){
        return $this->hasMany(Product::class , 'category_id' ,'id');
    }
    public function children(){
        return $this->hasMany(Category::class , 'parent_id' ,'id');
    }
    public function parent(){
        return $this->belongsTo(Category::class , 'parent_id' , 'id')->withDefault([
            'name' => 'no Parent'
        ]);
    }
}
