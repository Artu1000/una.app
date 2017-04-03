<?php

namespace App\Console\Commands\Database;

use Activation;
use Illuminate\Console\Command;
use Reminder;

class UsersAndRelatedTablesClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:users:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean the users and related tables from expired lines (reminders, activations, ...)';

    /**
     * UsersAndRelatedTablesClean constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->line('Cleaning activations and reminders tables from expired lines ...');

        Activation::removeExpired();
        $this->info('✔ Activations table cleaned.');

        Reminder::removeExpired();
        $this->info('✔ Reminders table cleaned.');
    }
}
