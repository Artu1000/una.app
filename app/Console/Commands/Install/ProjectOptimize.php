<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class ProjectOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We optimize the project according to the environment';

    /**
     * StoragePrepare constructor.
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
        $this->line('▶ Executing composer dump-autoload ...');
        exec('composer dump-autoload -o');
        $this->info('✔ composer dump autoload executed' . PHP_EOL);

        $this->line('▶ Executing artisan clear-compiled ...');
        exec('php artisan clear-compiled');
        $this->info('✔ artisan clear-compiled executed' . PHP_EOL);

        $this->line('▶ Executing artisan optimize ...');
        exec('php artisan optimize');
        $this->info('✔ artisan optimize executed' . PHP_EOL);

        if (config('app.env') != 'local') {
            $this->line('▶ Executing artisan cache:clear ...');
            exec('php artisan cache:clear');
            $this->info('✔ artisan cache:clear executed' . PHP_EOL);

            $this->line('▶ Executing artisan route:clear ...');
            exec('php artisan route:clear');
            $this->info('✔ artisan route:clear executed' . PHP_EOL);

            $this->line('▶ Executing artisan config:clear ...');
            exec('php artisan config:clear');
            $this->info('✔ artisan config:clear executed' . PHP_EOL);

            $this->line('▶ Executing artisan config:cache ...');
            exec('php artisan config:cache');
            $this->info('✔ artisan config:cache executed' . PHP_EOL);

//            $this->line('▶ Executing artisan route:cache ...');
//            exec('php artisan route:cache');
//            $this->info('✔ artisan route:cache executed' . PHP_EOL);
        } else {
            $this->line('▶ Executing artisan ide-helper:generate ...');
            exec('php artisan ide-helper:generate');
            $this->info('✔ artisan ide-helper:generate executed' . PHP_EOL);
        }
    }
}
