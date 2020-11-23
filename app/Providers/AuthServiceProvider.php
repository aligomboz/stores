<?php

namespace App\Providers;

use App\Product;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
       // \App\Product::class => \App\Policies\ProductPolicy::class
       //,في حال كان اسم المودل يختلف عن اسم البوليسي نستخدم يدوي


       
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::before(function($user , $ability){//هاد في حال بدي اقلو فوت دون فحص
            if($user->type == 'superAdmin'){
                return true;
            }

        });
        Gate::define('products.delete' , function(User $user , Product $product){
            if($product->user_id != $user->id){
                return Response::deny('you are not the owner of the Product');
            }
           return $user->hasPermission('products.delete');
        });
        Gate::define('products.edit' , function(User $user){
            return $user->hasPermission('products.edit');

         });

         Gate::define('Category.create' , 'App\Policies\CategoryPolicy@create');
        /*
        Gate::define('products.delete' , function($user){
            if($user->type == 'superAdmin'){
                return true;
            }return false;
        });

        Gate::define('products.edit' , function($user){
            if($user->type == 'superAdmin'){
                return true;
            }return false;
        });
        */
    }

}
