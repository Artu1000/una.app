<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoragePrepare extends Command
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
        // install instructions
        // http://blog.bobbyallen.me/2014/10/21/installing-mailcatcher-support-in-laravel-homestead/

        // configuration instructions
        // http://blog.enge.me/post/installing-mailcatcher-laravel-homestead

        // other infos on https://gist.github.com/maxxscho/8e6a5e6378f969c6c8b6
    }
}
