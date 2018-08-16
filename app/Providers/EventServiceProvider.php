<?php

namespace App\Providers;

use App\Events\ProxyCreatedEvent;
use App\Listeners\ProxyCreatedListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProxyCreatedEvent::class => [
            ProxyCreatedListener::class,
        ],
    ];
}
