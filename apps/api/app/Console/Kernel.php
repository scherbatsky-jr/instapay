<?php

namespace App\Console;

use Api\Users\Console\AddUserCommand;
use Api\Users\Console\LogoutUserCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddUserCommand::class,
        LogoutUserCommand::class,
    ];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('schedule.user_logout')) {
            $schedule->command('scd:users:logout --dry-run=0')
                ->at(config('app.user.logout_time'))
                ->sendOutputTo(storage_path('logs/user_logout.log'));
        }
    }
}
