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
    protected $description = 'Project optimizations according to the detected environment';
    
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
        if (config('app.env') != 'local') {
            // we only cache routes if the app is not multilingual
            if(!config('settings.multilingual')){
                $this->line('▶ Executing artisan route:cache ...');
                exec('php artisan route:cache');
                $this->info('✔ artisan route:cache executed' . PHP_EOL);
            }
        } else {
            $this->line('▶ Executing composer dump-autoload ...');
            exec('composer dump-autoload -o');
            $this->info('✔ composer dump autoload executed' . PHP_EOL);
            
            $this->line('▶ Executing artisan view:clear ...');
            exec('php artisan view:clear');
            $this->info('✔ artisan view:clear executed' . PHP_EOL);
            
            $this->line('▶ Executing artisan cache:clear ...');
            exec('php artisan cache:clear');
            $this->info('✔ artisan cache:clear executed' . PHP_EOL);
    
            $this->line('▶ Executing artisan clear-compiled ...');
            exec('php artisan clear-compiled');
            $this->info('✔ artisan clear-compiled executed' . PHP_EOL);
            
            $this->line('▶ Executing artisan ide-helper:generate ...');
            exec('php artisan ide-helper:generate');
            $this->info('✔ artisan ide-helper:generate executed' . PHP_EOL);
            
            $this->line('▶ Executing artisan ide-helper:models --nowrite ...');
            exec('php artisan ide-helper:models --nowrite');
            $this->info('✔ artisan ide-helper:models --nowrite executed' . PHP_EOL);
            
            $this->line('▶ Executing artisan ide-helper:meta ...');
            exec('php artisan ide-helper:meta');
            $this->info('✔ artisan ide-helper:meta executed' . PHP_EOL);
    
            $this->line('▶ Executing artisan optimize ...');
            exec('php artisan optimize');
            $this->info('✔ artisan optimize executed' . PHP_EOL);
        }
    }
}
