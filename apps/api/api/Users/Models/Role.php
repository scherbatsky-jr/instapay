<?php

namespace Api\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_BROKER_MANAGER = 'Broker Manager';
    public const ROLE_BROKER_SALES = 'Broker Sales';
    public const ROLE_OPERATOR = 'Operator';
    public const ROLE_SUPER_ADMIN = 'Super Admin';

    public function activeUsers()
    {
        return $this->users->filter(function ($user) {
            $start = $user->pivot->start_date;
            $end = $user->pivot->end_date;
            $now = Carbon::now();

            return (!$start || $now->gte($start)) && (!$end || $now->lte($end));
        });
    }

    public static function getRole($role)
    {
        return self::query()
            ->where('name', $role)
            ->first();
    }
}
