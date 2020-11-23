<?php

namespace App\Events;

use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class OrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //$user = User::where('type' , 'superAdmin')->first();
        return new Channel('orders' /*$this->order->id*/ /*Auth::id() يعمل لنفس الشخص*/ /*$user->id*/);
    }
    public function broadcastAs(){ // بحدد الاسم
        return 'orders_created';
    }
    public function broadcastWith(){
        return [
            'user' =>Auth::user(),
            'order' => $this->order,
        ];
    }
}
