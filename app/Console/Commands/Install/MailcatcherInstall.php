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
        if(config('env') == 'local'){
            // we install / reinstall ruby
            $this->info('Installing Ruby ...');
            $output = exec('sudo apt-get install ruby1.9.1-dev');
            $this->line($output);
            $this->info('✔ Ruby installation done');

            $this->line(' ');

            // install mailcatcher
            $this->line('Installing Mailcatcher ...');
            $output = exec('sudo gem install mailcatcher');
            $this->line($output);

            $this->line(' ');

            // give configuration instructions
            $this->line('Please execute the following command into another terminal :');
            $this->line(' ');
            $this->line('vim /etc/init/mailcatcher.conf');

            $this->line(' ');

            $this->line('Then, copy the following content into the "mailcatcher.conf" file');
            $this->line(' ');
            $this->line('description "Mailcatcher"');
            $this->line('start on runlevel [2345]');
            $this->line('stop on runlevel [!2345]');
            $this->line('respawn');
            $this->line('exec /usr/bin/env $(which mailcatcher) --ip 192.168.10.10');

            $this->line(' ');

            $this->line('Finaly, make sure that your .env folder at the root of your project is configured with those values');
            $this->line(' ');
            $this->line('MAIL_DRIVER=smtp');
            $this->line('MAIL_HOST=192.168.10.10');
            $this->line('MAIL_PORT=1025');
            $this->line('MAIL_USERNAME=null');
            $this->line('MAIL_PASSWORD=null');
            $this->line('MAIL_ENCRYPTION=null');

            $this->line(' ');

            $this->info('✔ Mailcatcher installation done');

            $this->line(' ');

            $this->confirm('Press [Enter] to continue once this is done', true);
        };
    }
}
