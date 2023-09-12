<?php

namespace Api\Users\Console;

use Api\UserEvents\Models\ApiAccessEvent;
use Api\UserEvents\Models\LoginEvent;
use Api\UserEvents\Models\LogoutEvent;
use Api\Users\Models\Role;
use Api\Users\Models\User;
use App\Auth\LoginProxy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LogoutUserCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logout operator users';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scds:users:logout {--dry-run=1}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        $activeUsers = User::role([Role::ROLE_OPERATOR])
            ->whereHas('tokens', function ($query) {
                $query->where('revoked', false);
            })
            ->get();

        foreach ($activeUsers as $user) {
            $this->info(sprintf('Processing user %s', $user->getProfile('en')->given_name));

            if ($dryRun) {
                continue;
            }

            $access_tokens = DB::table('oauth_access_tokens')
                ->where('user_id', '=', $user->id);

            $access_tokens->update([
                'revoked' => true,
            ]);

            $access_tokens = $access_tokens->get()
                ->toArray();

            DB::table('oauth_refresh_tokens')
                ->whereIn('access_token_id', array_column($access_tokens, 'id'))
                ->update([
                    'revoked' => true,
                ]);

            Cookie::queue(Cookie::forget(LoginProxy::REFRESH_TOKEN));

            // if user forgot to logout from system at evening then we should set last access time as logout time
            $this->fixLastLogout($user);
        }

        if ($dryRun) {
            $this->warn('Changes are not saved. Run with --dry-run=0');
        }
    }

    protected function fixLastLogout(User $user)
    {
        $lastLoginAt = LoginEvent::where('user_id', $user->id)->orderBy('datetime', 'desc')
            ->first();

        $lastLogoutAt = LogoutEvent::where('user_id', $user->id)->orderBy('datetime', 'desc')
            ->first();

        $lastAcessedAt = ApiAccessEvent::where('user_id', $user->id)->orderBy('datetime', 'desc')
            ->first();

        if ($lastLoginAt && $lastLogoutAt && ($lastLogoutAt->datetime < $lastLoginAt->datetime)) {
            LogoutEvent::create([
                'datetime' => ($lastAcessedAt && $lastAcessedAt->datetime > $lastLoginAt->datetime) ? $lastAcessedAt->datetime : $lastLoginAt->datetime,
                'user_id' => $user->id,
            ]);
        }
    }
}
