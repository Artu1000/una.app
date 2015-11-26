<?php

namespace App\Console;

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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\Install\StoragePrepare::class,
        \App\Console\Commands\Install\MailcatcherInstall::class,
        \App\Console\Commands\Install\ProjectInstall::class,
        \App\Console\Commands\Database\UsersAndRelatedTablesClean::class,
        \App\Console\Commands\Image\ImageGenerate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // we schedule the users related tables from expired lines
        $schedule->command('database:users:clean')->dailyAt('23:00')->sundays();
    }
}
