<?php

namespace Api\UserEvents;

use Api\UserEvents\Listeners\LoginListener;
use Api\UserEvents\Listeners\LogoutListener;
use Api\Users\Events\LoginEvent as AuthLoginEvent;
use Api\Users\Events\LogoutEvent as AuthLogoutEvent;
use App\Providers\EventServiceProvider;

class UserEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        AuthLoginEvent::class => [
            LoginListener::class,
        ],
        AuthLogoutEvent::class => [
            LogoutListener::class,
        ],
    ];
}
