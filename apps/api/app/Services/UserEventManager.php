<?php

namespace App\Services;

use Api\UserEvents\Models\ApiAccessEvent;
use Api\UserEvents\Models\LoginEvent;
use Api\UserEvents\Models\LogoutEvent;
use Api\Users\Models\User;
use App\Interfaces\UserEventManagerInterface;
use Carbon\Carbon;

class UserEventManager implements UserEventManagerInterface
{
    public function createLoginRecord(User $user): LoginEvent
    {
        return LoginEvent::create([
            'datetime' => Carbon::now(),
            'user_id' => $user->id,
        ]);
    }

    public function createLogoutRecord(User $user): LogoutEvent
    {
        return LogoutEvent::create([
            'datetime' => Carbon::now(),
            'user_id' => $user->id,
        ]);
    }

    public function logUserAccess(User $user): ApiAccessEvent
    {
        $lastAccess = $this->getLastAccessRecord($user);

        if (!$lastAccess) {
            return ApiAccessEvent::create([
                'datetime' => Carbon::now(),
                'frequency' => 1,
                'user_id' => $user->id,
            ]);
        }

        $lastAccess->update([
            'datetime' => Carbon::now(),
            'frequency' => $lastAccess->frequency + 1,
        ]);

        return $lastAccess;
    }

    protected function getLastAccessRecord($user): ?ApiAccessEvent
    {
        $today = Carbon::now()->format('Y-m-d');

        return ApiAccessEvent::where('datetime', 'LIKE', '%'.$today.'%')
            ->where('user_id', $user->id)
            ->first();
    }
}
