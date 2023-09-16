<?php

namespace Api\Users\Console;

use Api\Users\Models\Role;
use Api\Users\Models\User;
use Api\Users\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUserCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new user';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {email} {password} {role} {--dry-run=1}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        $email = $this->argument('email');

        if (User::where('email', $email)->first()) {
            $this->error('The email already exists. Please try another email.');

            return;
        }

        $password = $this->argument('password');
        $role = $this->argument('role');

        if ('admin' === $role) {
            $role = Role::getRole(Role::ROLE_ADMIN);
        } elseif ('seller' === $role) {
            $role = Role::getRole(Role::ROLE_SELLER);
        } elseif ('customer' === $role) {
            $role = Role::getRole(Role::ROLE_CUSTOMER);
        } else {
            $this->error('Invalid Role. Please enter either admin, broker-manager, broker-sales or operator.');

            return;
        }

        if (!$dryRun) {
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'username' => $email
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);

            $this->info('A new user has been created.');
        }

        if ($dryRun) {
            $this->warn('Running with dry run enabled. User will not be created. Run with --dry-run=0');
        }

        return;
    }
}
