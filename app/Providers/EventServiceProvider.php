<?php

namespace App\Providers;

use App\Events\OrderEvent;
use App\Listeners\SendOrderCreateEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderEvent::class =>[
            SendOrderCreateEmail::class,
        ],
        'App\Events\ProductCreated' =>[
            'App\Listeners\UpdateCache', //في حال لوبدي انشاء الايفينت و اليسينير مع بعض 
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
