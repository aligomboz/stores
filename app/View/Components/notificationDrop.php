<?php

namespace App\View\Components;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class notificationDrop extends Component
{
    public $items = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->items = Auth::user()->notifications;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $countNotifications =  DatabaseNotification::where('read_at','=',null)->get();

        
    //   return $countNotifications;
        return view('components.notifications-dropdown',compact('countNotifications'));
    }
}
