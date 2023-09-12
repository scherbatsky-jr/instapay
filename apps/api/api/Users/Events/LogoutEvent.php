<?php

namespace Api\Users\Events;

use Api\Users\Models\User;

class LogoutEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
