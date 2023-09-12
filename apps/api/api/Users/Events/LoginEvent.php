<?php

namespace Api\Users\Events;

use Api\Users\Models\User;

class LoginEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
