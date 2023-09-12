<?php

namespace App\Http\Middleware;

use App\Services\UserEventManager;
use Illuminate\Http\Request;

class TrackUserAccess
{
    public function handle(Request $request, \Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $this->getUserEventManager()->logUserAccess($user);
        }

        return $next($request);
    }

    protected function getUserEventManager()
    {
        return resolve(UserEventManager::class);
    }
}
