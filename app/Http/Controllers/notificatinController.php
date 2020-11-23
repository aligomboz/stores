<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notificatinController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();

        return view('notifications', [
            'notifications' => $user->notifications, //unReadNotification
        ]);

        $user->unreadNotifications()->markAsRead();//لوبدي اقرا الكل 
    }

    public function read($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->to($notification->data['action']);
    }
}
