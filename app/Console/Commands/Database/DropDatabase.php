<?php

namespace App\Console\Commands\Database;

use DB;
use Eloquent;
use Illuminate\Console\Command;

class DropDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:drop {--force : Whether the command should be forced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop database';

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

        $tables = [];

        if ($this->confirmClear($force)) {
            $this->line('Dropping database...');

            // we disable the foreign keys check
            Eloquent::unguard();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // we list all the tables
            foreach (DB::select('SHOW TABLES') as $v) {
                $tables[] = array_values((array)$v)[0];
            }

            // we drop the list of tables
            foreach ($tables as $table) {
                \Schema::drop($table);
                $this->info('✔ Table ' . $table . ' has been dropped.');
            }

            // we activate the foreign keys check
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function confirmClear($force)
    {
        if ($force) {
            return true;
        }
        if ($this->confirm("This command will clear all the tables in the database and cannot be undone. Are you sure you want to continue?", false)) {
            return true;
        }

        return false;
    }
}
