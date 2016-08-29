<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class NpmInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npm:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process npm dependencies installation and update';

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
        $this->line('Deleting node_modules folder ...');
        Console::execWithOutput('rm -rf node_modules', $this);
        $this->info('✔ node_modules folder deleted' . PHP_EOL);

        $this->line('Disabling npm progress bar ...');
        Console::execWithOutput('npm set progress=false', $this);
        $this->info('✔ npm progress bar disabled' . PHP_EOL);

        $this->line('Processing npm cache clean ...');
        Console::execWithOutput('npm cache clean', $this);
        $this->info('✔ npm cache clean done' . PHP_EOL);

        $this->line('Processing gulp install ...');
        Console::execWithOutput('npm install gulp --save-dev', $this);
        $this->info('✔ gulp installed' . PHP_EOL);
    
        $this->line('Processing gulp-shell install ...');
        Console::execWithOutput('npm install gulp-shell --save-dev', $this);
        $this->info('✔ gulp-shell installed' . PHP_EOL);

        $this->line('Processing laravel-elixir install ...');
        Console::execWithOutput('npm install laravel-elixir --save-dev', $this);
        $this->info('✔ laravel-elixir installed' . PHP_EOL);
    
        $this->line('Processing laravel-elixir-delete install ...');
        Console::execWithOutput('npm install laravel-elixir-delete --save-dev', $this);
        $this->info('✔ laravel-elixir-delete installed' . PHP_EOL);
    
        $this->line('Processing laravel-elixir-imagemin install ...');
        Console::execWithOutput('npm install laravel-elixir-imagemin --save-dev', $this);
        $this->info('✔ laravel-elixir-imagemin installed' . PHP_EOL);

        $this->line('Processing npm rebuild node-sass ...');
        Console::execWithOutput('npm rebuild node-sass', $this);
        $this->info('✔ npm node-sass rebuilt' . PHP_EOL);
    }
}
