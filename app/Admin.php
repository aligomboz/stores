<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User //غيرت بدل ما كام مودل
{
    use Notifiable;
}
