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
        // npm / node and all necessary dependencies install & update
        $this->call('npm:install');

        $this->line(' ');

        // bower install
        $this->line('Processing bower dependencies install ...');
        \Console::execWithOutput('bower install', $this);
        $this->info('✔ Bower dependencies installed');

        $this->line(' ');

        // bower update
        $this->line('Processing bower dependencies update ...');
        \Console::execWithOutput('bower update', $this);
        $this->info('✔ Bower dependencies updated');

        $this->line(' ');

        // composer install
        $this->line('Processing composer dependencies install ...');
        \Console::execWithOutput('composer install', $this);
        $this->info('✔ Composer dependencies installed');

        $this->line(' ');

        // composer update
        $this->line('Processing composer dependencies update ...');
        \Console::execWithOutput('composer update', $this);
        $this->info('✔ Composer dependencies updated');

        $this->line(' ');

        // apt-get update
        $this->line('Processing apt-get update ...');
        \Console::execWithOutput('sudo apt-get update', $this);
        $this->info('✔ apt-get updated');

        $this->line(' ');

        // image optimization
        $this->line('Processing OptiPNG and jpegoptim image optimizers install ...');
        \Console::execWithOutput('sudo apt-get install optipng jpegoptim', $this);
        $this->info('✔ OptiPNG and jpegoptim installed');

        $this->line(' ');

        // storage file verification
        $this->call('storage:prepare');

        $this->line(' ');

        // generate application key
        $this->line('Generating new Laravel app key ...');
        \Console::execWithOutput('php artisan key:generate', $this);
        $this->info('✔ New app key generated');

        $this->line(' ');

        $this->line('Processing elixir task ...');
        \Console::execWithOutput('gulp --production', $this);
        $this->info('✔ Sass and js compiled and minified');

        $this->line(' ');

        // mailcatcher install
        if ($this->ask('Do you want to install mailcatcher on your project ? [y/N]', false)) {
            $this->call('mailcatcher:install');
        };

        $this->line(' ');

        // we execute migrations
        $this->line('Executing migrations ...');
        \Console::execWithOutput('php artisan migrate', $this);
        $this->info('✔ Migration done');

        // we prepare symlinks
        $this->call('npm:install');

        // seeds
        if ($this->ask('Do you want to execute the database seed on your project ? [y/N]', false)) {
            $this->line('Executing seeds ...');
            \Console::execWithOutput('php artisan migrate:refresh', $this);
            \Console::execWithOutput('php artisan db:seed', $this);
            $this->info('✔ Seed done');
        }

        $this->info('✔ Project installation complete !');
    }
}
