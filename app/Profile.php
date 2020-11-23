<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'city' , 'user_id' , 'phone'
    ];
    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
