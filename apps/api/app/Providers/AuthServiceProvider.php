<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::routes(
            function ($router) {
                $router->forAccessTokens();
                // Uncomment for allowing personal access tokens
                // $router->forPersonalAccessTokens();
                $router->forTransientTokens();
            }
        );

        $access_token_lifetime = config('auth.access_token_lifetime.default');

        $refresh_token_lifetime = config('auth.refresh_token_lifetime.default');

        Passport::tokensExpireIn(
            Carbon::now()->addSeconds($access_token_lifetime)
        );

        Passport::refreshTokensExpireIn(
            Carbon::now()->addSeconds($refresh_token_lifetime)
        );

        $this->registerPolicies();
    }
}
