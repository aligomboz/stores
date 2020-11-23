<?php

use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});*/


Route::prefix('admin')->namespace('Admin')->middleware(['auth','verified' , 'checkUser:superAdmin,admin'])->group(function(){
    Route::resource('category' ,'CategoriesController');
    Route::get('products/trash', 'ProductController@trash');
    Route::put('products/{id}/restore', 'ProductController@restore')->name('products.restore');
    Route::delete('products/{id}/force-delete', 'ProductController@forceDelete')->name('products.forceDelete');
    Route::resource('products', 'ProductController');
    Route::resource('user', 'UsreController');
});
Route::namespace('Admin\Auth')->prefix('admin')->group(function(){
    Route::get('login/' , 'LoginController@showLoginForm')->name('login');
    Route::post('login/' , 'LoginController@login');

});


//Route::post('deleteGallery/{id}','Admin\ProductController@deleteGallery')->name('deleteGallery');

Route::get('custom/login','Auth\CustomLoginController@showLoginForm')->name('custom-login');
Route::post('custom/login','Auth\CustomLoginController@login');
Auth::routes([
    'register' =>true,
    'verify' =>true
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('{locale?}')->where(['locale'=>'[a-z]{2}'])->group(function(){
    Route::get('/' ,'IndexController@index')->name('frontPage');
});

Route::get('/send-cookie', function () {
    Cookie::queue(Cookie::make('cart' , 'product 2' , 10));
    return view('cooki');

    /* الطريقة الاولى
    return response('hello , we have set a cookie for you !')
    ->cookie(Cookie::make('cart' , 'product 1' , 90));
    */
});
Route::get('notifications', 'notificatinController@index');
Route::get('notifications/{id}', 'notificatinController@read')->name('notification.read');
Route::get('show/product/{product}' , 'ProductController@show')->name('show.product');

Route::get('cart', 'CartController@index')->name('cart');
Route::post('cart', 'CartController@store');

Route::get('checkout', 'CheckoutController@index')->name('checkout');
Route::post('checkout','CheckoutController@checkout');

Route::get('orders', 'OrdersController@index')->name('orders');

Route::get('paypal/return' , 'CheckoutController@paypalReturn')->name('paypal.return');
Route::get('paypal/cancel' , 'CheckoutController@paypalCancel')->name('paypal.cancel');