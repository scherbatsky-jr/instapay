<?php

namespace App\Exceptions;

use Api\Users\Models\User;

class UserDisabledException extends \Exception
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        parent::__construct(
            sprintf(
                'User `%s` is disabled.',
                $user->username
            )
        );
    }

    public function getUser()
    {
        return $this->user;
    }
}
