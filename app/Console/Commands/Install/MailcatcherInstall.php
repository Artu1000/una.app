<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;

class MailcatcherInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailcatcher:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install mailcatcher if no installation is detected';

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
        if(env('APP_ENV') == 'local'){
            // we install / reinstall ruby
            $this->info('Installing Ruby ...');
            $output = exec('sudo apt-get install ruby1.9.1-dev');
            $this->info($output);

            $this->info(' ');

            // install mailcatcher
            $this->info('Installing Mailcatcher ...');
            $output = exec('sudo gem install mailcatcher');
            $this->info($output);

            $this->info(' ');

            // give configuration instructions
            $this->info('Please execute the following command into another terminal :');
            $this->info(' ');
            $this->info('vim /etc/init/mailcatcher.conf');

            $this->info(' ');

            $this->info('Then, copy the following content into the "mailcatcher.conf" file');
            $this->info(' ');
            $this->info('description "Mailcatcher"');
            $this->info('start on runlevel [2345]');
            $this->info('stop on runlevel [!2345]');
            $this->info('respawn');
            $this->info('exec /usr/bin/env $(which mailcatcher) --ip 192.168.10.10');

            $this->info(' ');

            $this->info('Finaly, make sure that your .env folder at the root of your project is configured with those values');
            $this->info(' ');
            $this->info('MAIL_DRIVER=smtp');
            $this->info('MAIL_HOST=192.168.10.10');
            $this->info('MAIL_PORT=1025');
            $this->info('MAIL_USERNAME=null');
            $this->info('MAIL_PASSWORD=null');
            $this->info('MAIL_ENCRYPTION=null');

            $this->info(' ');

            $this->confirm('Press [Enter] to continue once this is done', true);
        };
    }
}
