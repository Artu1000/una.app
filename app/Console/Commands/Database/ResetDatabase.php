<?php

namespace App\Console\Commands\Database;

use Illuminate\Console\Command;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:reset {--force : Whether the command should be forced}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database by dropping, migrating and seeding';
    
    /**
     * DropDatabase constructor.
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
        // we get the option value
        $force = boolval($this->option('force'));
        
        if ($this->confirmReset($force)) {
            // we drop the database
            $this->call('database:drop', [
                '--force' => true,
            ]);
            // we execute the migrations
            $this->call('migrate');
            // we execute the seed
            $this->call('db:seed');
            $this->info('✔ Migrations and seed done' . PHP_EOL);
        } else {
            $this->migrate();
        }
    }
    
    /**
     * @param $force
     * @return bool
     */
    private function confirmReset($force)
    {
        // if forced, we return true
        if ($force) {
            return true;
        }
        
        // we prompt the user
        if ($this->confirm('Do you want to execute the database reset on your project ?', false)) {
            // we ask the user confirmation
            if ($this->confirm('This command will destroy you database and rebuild a fresh one with seeds. Do you confirm ?', false)) {
                return true;
            }
            
            return false;
        }
        
        return false;
    }
    
    /**
     *
     */
    public function migrate()
    {
        // we execute migrations
        $this->line('Executing migrations ...');
        // we execute the migrations
        $this->call('migrate');
        $this->info('✔ Migration done' . PHP_EOL);
    }
}
