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
    protected $description = 'Install the project and all its dependencies.';

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
        // npm & node and all necessary dependencies install & update
        if (config('app.env') == 'local') {
            if ($this->ask('Do you want to execute the npm install process on your project ? [y/N]', false)) {
                $this->call('npm:install');
            }
        }

        // bower install
        $this->line('Processing bower dependencies install ...');
        \Console::execWithOutput('bower install', $this);
        $this->info('✔ Bower dependencies installed' . PHP_EOL);

        // bower update
        $this->line('Processing bower dependencies update ...');
        \Console::execWithOutput('bower update', $this);
        $this->info('✔ Bower dependencies updated' . PHP_EOL);

        // composer install
        $this->line('Processing composer self-update ...');
        \Console::execWithOutput('sudo composer self-update', $this);
        $this->info('✔ Composer self-updated' . PHP_EOL);

        // composer install
        $this->line('Processing composer dependencies install ...');
        \Console::execWithOutput('composer install', $this);
        $this->info('✔ Composer dependencies installed' . PHP_EOL);

        // composer update
        $this->line('Processing composer dependencies update ...');
        \Console::execWithOutput('composer update', $this);
        $this->info('✔ Composer dependencies updated' . PHP_EOL);

        // apt-get update
        $this->line('Processing apt-get update ...');
        \Console::execWithOutput('sudo apt-get update', $this);
        $this->info('✔ apt-get updated' . PHP_EOL);

        // image optimization
        $this->line('Processing OptiPNG and jpegoptim image optimizers install ...');
        \Console::execWithOutput('sudo apt-get install optipng jpegoptim', $this);
        $this->info('✔ OptiPNG and jpegoptim installed' . PHP_EOL);

        // storage file verification
        $this->call('storage:prepare');

        // generate application key
        $this->line('Generating new Laravel app key ...');
        \Console::execWithOutput('php artisan key:generate', $this);
        $this->info('✔ New app key generated' . PHP_EOL);

        // processing gulp task
        if (config('app.env') === 'local') {
            $this->line('Processing elixir task ...');
            \Console::execWithOutput('gulp --production', $this);
            $this->info('✔ Sass and js compiled and minified' . PHP_EOL);
        }

        // mailcatcher install
        if (config('app.env') === 'local') {
            if ($this->ask('Do you want to install mailcatcher on your project ? [y/N]', false)) {
                $this->call('mailcatcher:install');
            };
        }

        // we prepare symlinks
        $this->call('symlinks:prepare');

        // seeds
        if ($this->ask('Do you want to execute the database seed on your project ? [y/N]', false)) {
            $this->line('Executing seeds ...');
            \Console::execWithOutput('php artisan migrate:refresh', $this);
            \Console::execWithOutput('php artisan db:seed', $this);
            $this->info('✔ Seed done' . PHP_EOL);
        } else {
            // we execute migrations
            $this->line('Executing migrations ...');
            \Console::execWithOutput('php artisan migrate', $this);
            $this->info('✔ Migration done' . PHP_EOL);
        }

        // autoload files dump and optimization
        $this->line('Executing autoload files clear and optimization ...');
        \Console::execWithOutput('composer dump-autoload -o', $this);
        $this->info('✔ Autoload files cleared and optimized' . PHP_EOL);

        $this->info('✔ Project installation complete !' . PHP_EOL);
    }
}
