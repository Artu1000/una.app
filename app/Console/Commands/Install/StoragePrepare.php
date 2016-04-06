<?php

namespace App\Console\Commands\Install;

use Console;
use Illuminate\Console\Command;

class StoragePrepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the storage folder to avoid errors on installation';

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
        // we set the folder to verify
        $to_create = [
            storage_path(),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('app'),
            storage_path('app/schedules'),
            storage_path('app/news'),
            storage_path('app/partners'),
            storage_path('app/settings'),
            storage_path('app/e-shop'),
            storage_path('app/pages'),
            storage_path('app/users'),
            storage_path('app/home'),
            storage_path('app/home/slides'),
        ];
        // we execute the verification
        $created = [];
        foreach ($to_create as $folder) {
            if (!is_dir($folder)) {
                mkdir($folder);
                $created[] = $folder;
            }
        }

        if (!empty($created)) {
            $this->info('✔ Storage folder prepared. Folders created :');
            foreach ($created as $folder) {
                $this->line('- ' . $folder);
            }
        } else {
            $this->info('✔ No folder were missing');
        }

        // settings.json existence verification
        if (!is_file(storage_path('app/settings/settings.json'))) {
            Console::execWithOutput('php artisan db:seed --class=SettingsTableSeeder', $this);
        }
        
        // we give the correct rights to the storage folder
        exec('sudo chmod -R g+w ' . storage_path());
        exec('sudo chgrp -R www-data ' . storage_path());
    }
}
