<?php

namespace Tests\Authorization;

use Api\Users\Models\Role;

class OperatorLoginTest extends AbstractLoginTest
{
    public const INVALID_ROLE = Role::ROLE_ADMIN;
    public const USER_ROLE = Role::ROLE_OPERATOR;
}
