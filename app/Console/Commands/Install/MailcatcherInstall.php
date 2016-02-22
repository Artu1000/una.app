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
        // we install / reinstall ruby
        $this->info('Installing Ruby ...');
        \Console::execWithOutput('sudo apt-get install ruby1.9.1-dev libsqlite3-dev', $this);
        $this->info('✔ Ruby installation done' . PHP_EOL);

        // install mailcatcher
        $this->line('Installing Mailcatcher ...');
        \Console::execWithOutput('sudo gem install mailcatcher', $this);
        $this->info('✔ Mailcatcher installation done' . PHP_EOL);

        $this->line('Executing Mailcatcher ...');
        \Console::execWithOutput('mailcatcher', $this);
        $this->info('✔ Mailcatcher is running' . PHP_EOL);

        $this->line(' ');

        // give configuration instructions
        $this->line('Please execute the following command into another terminal :' . PHP_EOL);

        $this->line('sudo vim /etc/init/mailcatcher.conf' . PHP_EOL);

        $this->line('Then, copy the following content into the "mailcatcher.conf" file' . PHP_EOL);

        $this->line('description "Mailcatcher"');
        $this->line('start on runlevel [2345]');
        $this->line('stop on runlevel [!2345]');
        $this->line('respawn');
        $this->line('exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0' . PHP_EOL);

        $this->line('Finaly, make sure that your .env folder at the root of your project is configured with those values' . PHP_EOL);

        $this->line('MAIL_DRIVER=smtp');
        $this->line('MAIL_HOST=localhost');
        $this->line('MAIL_PORT=1025');
        $this->line('MAIL_USERNAME=null');
        $this->line('MAIL_PASSWORD=null');
        $this->line('MAIL_ENCRYPTION=null' . PHP_EOL);

        $this->info('✔ Mailcatcher installation done' . PHP_EOL);
    }
}
