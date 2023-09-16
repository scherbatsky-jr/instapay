<?php

namespace Tests\Authorization;

use Api\Users\Models\Role;

class AdminLoginTest extends AbstractLoginTest
{
    public const INVALID_ROLE = Role::ROLE_OPERATOR;
    public const USER_ROLE = Role::ROLE_ADMIN;
}
