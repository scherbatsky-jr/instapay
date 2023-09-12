<?php

namespace App\Interfaces;

use Api\UserEvents\Models\ApiAccessEvent;
use Api\UserEvents\Models\LoginEvent;
use Api\UserEvents\Models\LogoutEvent;
use Api\Users\Models\User;

interface UserEventManagerInterface
{
    public function createLoginRecord(User $user): LoginEvent;

    public function createLogoutRecord(User $user): LogoutEvent;

    public function logUserAccess(user $user): ApiAccessEvent;
}
