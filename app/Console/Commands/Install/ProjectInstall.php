<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;

class ProjectInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the project and all its dependancies.';

    /**
     * Create a new command instance.
     *
     * @return void
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
        // apt-get update

        $this->info('processing apt-get update ...');
        $output = exec('sudo apt-get update');
        $this->info($output);

        $this->info(' ');

        // image optimization
        $this->info('Installing OptiPNG and jpegoptim image optimizers ...');
        $output = exec('sudo apt-get install optipng jpegoptim');
        $this->info($output);

        $this->info(' ');

        // prepare storage folder
        $this->call('storage:prepare');

        $this->info(' ');

        // composer install
        $this->info('Executing composer install / update ...');
        $output = exec('composer install');
        $this->info($output);
        $output = exec('composer update');
        $this->info($output);

        $this->info(' ');

        // bower install
        $this->info('Executing bower install / update ...');
        $output = exec('bower install');
        $this->info($output);
        $output = exec('bower update');
        $this->info($output);

        $this->info(' ');

        // generate application key
        $this->info('Generate new Laravel app key ...');
        $output = exec('php artisan key:generate');
        $this->info($output);

        $this->info(' ');

        // mailcatcher install
        $this->call('mailcatcher:install');
    }
}
