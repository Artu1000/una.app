<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class YarnInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yarn:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process yarn dependencies installation and update';

    /**
     * NpmInstall constructor.
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
        $output = null;
        exec('rm -rf node_modules', $output);
        if($output) $this->line($output);
        $this->info('✔ node_modules folder deleted' . PHP_EOL);

        $this->line('Processing yarn cache clean ...');
        $output = null;
        exec('yarn cache clean', $output);
        if($output) $this->line($output);
        $this->info('✔ yarn cache clean done' . PHP_EOL);

        $this->line('Processing gulp install ...');
        $output = null;
        exec('yarn add --dev gulp', $output);
        if($output) $this->line($output);
        $this->info('✔ gulp installed' . PHP_EOL);

        $this->line('Processing gulp-shell install ...');
        $output = null;
        exec('yarn add --dev gulp-shell', $output);
        if($output) $this->line($output);
        $this->info('✔ gulp-shell installed' . PHP_EOL);

        $this->line('Processing laravel-elixir install ...');
        $output = null;
        exec('yarn add --dev laravel-elixir', $output);
        if($output) $this->line($output);
        $this->info('✔ laravel-elixir installed' . PHP_EOL);

        $this->line('Processing laravel-elixir-remove install ...');
        $output = null;
        exec('yarn add --dev laravel-elixir-remove', $output);
        if($output) $this->line($output);
        $this->info('✔ laravel-elixir-remove installed' . PHP_EOL);

        $this->line('Processing gulp image install ...');
        $output = null;
        exec('yarn add --dev gulp-image', $output);
        if($output) $this->line($output);
        $this->info('✔ gulp-image installed' . PHP_EOL);
    }
}
