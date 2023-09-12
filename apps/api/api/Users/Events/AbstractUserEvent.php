<?php

namespace Api\Users\Events;

use Api\Users\Models\User;

abstract class AbstractUserEvent
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
