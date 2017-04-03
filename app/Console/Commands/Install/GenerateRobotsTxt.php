<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class GenerateRobotsTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robots:txt:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Re)generate the robots.txt file';

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
        $output = null;
        if(config('app.env') === 'production'){
            exec("echo '\nUser-agent: *\nDisallow:\nSitemap: " . route('sitemap.index') . "' | sudo tee " . public_path('robots.txt'), $output);
        } else {
            exec("echo '\nUser-agent: *\nDisallow: /' | sudo tee " . public_path('robots.txt'), $output);
        }
        if($output) $this->line($output);
    }

}
