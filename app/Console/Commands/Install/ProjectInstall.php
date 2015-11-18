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

    public function execWithOutput($cmd)
    {
        // Setup the file descriptors
        $descriptors = [
            0 => ['pipe', 'w'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        // Start the script
        $proc = proc_open($cmd, $descriptors, $pipes);

        // Read the stdin
        $stdin = stream_get_contents($pipes[0]);
        fclose($pipes[0]);

        // Read the stdout
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read the stderr
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // Close the script and get the return code
        $return_code = proc_close($proc);

        $this->info($stdin);
        $this->info($stdout);
        $this->info($stderr);
//        $this->info($return_code);

        if (strpos($stdout, 'continue?')) {
            $this->error('A confirmation has been asked during the shell command execution.');
            $this->error('Please manually execute the command "' . $cmd . '" to treat that particular case.');
            exit();
        }

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
        $this->execWithOutput('sudo apt-get update');

        $this->info(' ');

        // image optimization
        $this->info('Installing OptiPNG and jpegoptim image optimizers ...');
        $this->execWithOutput('sudo apt-get install optipng jpegoptim');

        $this->info(' ');

        // prepare storage folder
        $this->info('Preparing storage folders ...');
        $this->call('storage:prepare');

        $this->info(' ');

        // composer install
        $this->info('Executing composer install / update ...');
        $this->execWithOutput('composer install');
        $this->execWithOutput('composer update');

        $this->info(' ');

        // bower install
        $this->info('Executing bower install / update ...');
        $this->execWithOutput('bower install');
        $this->execWithOutput('bower update');

        $this->info(' ');

        // generate application key
        $this->info('Generate new Laravel app key ...');
        $this->execWithOutput('php artisan key:generate');

        $this->info(' ');

        // mailcatcher install
        if ($this->ask('Do you want to install mailcatcher on your project ? [y/N]', false)) {
            $this->call('mailcatcher:install');
        };

        $this->info(' ');

        // migrations
        $this->info('Executing migrations ...');
        $this->execWithOutput('php artisan migrate');

        $this->info(' ');

        // seeds
        if ($this->ask('Do you want to execute the database seed on your project ? [y/N]', false)) {
            $this->info('Executing seeds ...');
            $this->execWithOutput('php artisan db:seed');
        }

        $this->info(' ');

        $this->info('Project installation complete !');
    }
}
