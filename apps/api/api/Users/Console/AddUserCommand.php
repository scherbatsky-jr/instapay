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
    protected $signature = 'scds:user:add {email} {password} {role} {parentId?} {--dry-run=1}';

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
        $parentId = $this->argument('parentId');

        if ('admin' === $role) {
            $role = Role::getRole(Role::ROLE_ADMIN);
        } elseif ('broker-manager' === $role) {
            $role = Role::getRole(Role::ROLE_BROKER_MANAGER);
        } elseif ('broker-sales' === $role) {
            $role = Role::getRole(Role::ROLE_BROKER_SALES);

            if (!$parentId) {
                $this->error('Please enter user id of the parent.');

                return;
            }
        } elseif ('operator' === $role) {
            $role = Role::getRole(Role::ROLE_OPERATOR);
        } else {
            $this->error('Invalid Role. Please enter either admin, broker-manager, broker-sales or operator.');

            return;
        }

        if (!$dryRun) {
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'username' => $email,
                'parent_id' => $parentId,
            ]);

            User::fixTree();

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
