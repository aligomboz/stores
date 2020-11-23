<?php

use App\Order;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders.{id}', function($user/*, $id*/){
   /* $order = Order::findOrFail($id);
    return ($user->type === 'superAdmin' || $user->id === $order->user_id);*/
    return $user->type === 'superAdmin' /*|| (int) $user->id === (int) $id*/;

});