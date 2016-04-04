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
    protected $description = '(Re)install mailcatcher automatically';

    /**
     * MailcatcherInstall constructor.
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
        $this->line('▶ Installing Ruby ...');
        \Console::execWithOutput('sudo apt-get install ruby1.9.1-dev libsqlite3-dev -y', $this);
        $this->info('✔ Ruby installation done' . PHP_EOL);

        // install mailcatcher
        $this->line('▶ Installing Mailcatcher ...');
        \Console::execWithOutput('sudo gem install mailcatcher -y', $this);
        $this->info('✔ Mailcatcher installation done' . PHP_EOL);

        $this->line('▶ Executing Mailcatcher ...');
        \Console::execWithOutput('mailcatcher', $this);
        $this->info('✔ Mailcatcher is running' . PHP_EOL);

        // we add the auto start mailcatcher script
        $this->line('▶ Writing autoload script ...');

        \Console::execWithOutput("echo 'description \"Mailcatcher\"\nstart on runlevel [2345]\nstop on runlevel [!2345]\nrespawn\nexec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0' | sudo tee /etc/init/mailcatcher.conf", $this);
        $this->info('✔ Autoload script written' . PHP_EOL);
    }

}
