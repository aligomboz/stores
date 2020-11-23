<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Mail\OrderCreatedMail;
use App\Notifications\orderCreatedNotification;
use App\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCreateEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        $user =  User::where('type' , 'superAdmin')->first();
        $user->notify(new orderCreatedNotification($event->order));

        $order = $event->order;
        $name = $order->user->name;
        $order_id = $order->id;
        //$email = $order->user->email;
        $email = $user->email;
        $action =url(route('orders'));
        Mail::send(new OrderCreatedMail($email ,$name , $order_id , $action));
    }
}
