<?php

namespace Api\Users\Types;

use Api\Users\Models\User;

class UserGraphQLType
{
    public function profiles(User $user)
    {
        return $user->profiles;
    }
}
