<?php

namespace Api\UserEvents\Listeners;

use Api\Users\Events\LoginEvent as AuthLoginEvent;
use App\Services\UserEventManager;

class LoginListener
{
    protected $userEventManager;

    public function __construct(UserEventManager $userEventManager)
    {
        $this->userEventManager = $userEventManager;
    }

    public function handle(AuthLoginEvent $event): void
    {
        $user = $event->user;

        $this->getUserEventManager()
            ->createLoginRecord($user);
    }

    protected function getUserEventManager(): UserEventManager
    {
        return $this->userEventManager;
    }
}
