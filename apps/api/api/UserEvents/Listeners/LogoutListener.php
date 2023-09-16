<?php

namespace Api\UserEvents\Listeners;

use Api\Users\Events\LogoutEvent as AuthLogoutEvent;
use App\Services\UserEventManager;

class LogoutListener
{
    protected $userEventManager;

    public function __construct(UserEventManager $userEventManager)
    {
        $this->userEventManager = $userEventManager;
    }

    public function handle(AuthLogoutEvent $event): void
    {
        $user = $event->user;

        $this->getUserEventManager()
            ->createLogoutRecord($user);
    }

    protected function getUserEventManager(): UserEventManager
    {
        return $this->userEventManager;
    }
}
