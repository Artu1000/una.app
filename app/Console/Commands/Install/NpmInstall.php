<?php

namespace App\Console\Commands\Install;

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
        // we check if we have a local environment
        if (env('APP_ENV') == 'local') {

            $this->line(' ');

            $this->line('Processing npm cache clean ...');
            \Console::execWithOutput('npm cache clean', $this);
            $this->info('✔ npm cache clean done');

            $this->line(' ');

            $this->line('Processing npm self-update ...');
            \Console::execWithOutput('npm install npm', $this);
            $this->info('✔ npm self-updated');

            $this->line(' ');

            $this->line('Processing node install ...');
            \Console::execWithOutput('npm install n', $this);
            $this->info('✔ node installed');

            $this->line(' ');

            $this->line('Processing npm dependencies install ...');
            \Console::execWithOutput('npm install', $this);
            $this->info('✔ npm dependencies installed');

            $this->line(' ');

            $this->line('Processing npm dependencies update ...');
            \Console::execWithOutput('npm update', $this);
            $this->info('✔ npm dependencies updated');

            $this->line(' ');

            $this->line('Processing npm rebuild node-sass ...');
            \Console::execWithOutput('npm rebuild node-sass', $this);
            $this->info('✔ npm node-sass rebuilt');
        };
    }
}
