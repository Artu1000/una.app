<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class GenerateRobotTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:robot.txt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Re)generate the robot.txt file';

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
        if(config('app.env') === 'production'){
            Console::execWithOutput("echo '\nUser-agent: *\nDisallow:\nSitemap: " . route('sitemap.index') . "' | sudo tee " . public_path('robots.txt'), $this);
        } else {
            Console::execWithOutput("echo '\nUser-agent: *\nDisallow: /' | sudo tee " . public_path('robots.txt'), $this);
        }
    }

}