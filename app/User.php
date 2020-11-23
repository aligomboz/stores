<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable , HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','address','city','country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products(){
        return $this->hasMany(Product::class , 'user_id' , 'id');
    }
    public function profils(){
        return $this->hasOne(Profile::class , 'user_id' , 'id');
    }

    public function hasPermission($name){
        return DB::table('users_permissions')
        ->where('user_id' , $this->id)
        ->where('permission' , $name)
        ->count();
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function carts(){
        return $this->hasMany(Cart::class);
    }
    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }
    public function routeNotificationForNexmo($notification = null){
        return $this->profils->phone;
    }
}
