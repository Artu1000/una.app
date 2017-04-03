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
            // public
            public_path('libraries'),
            public_path('img'),
            public_path('files'),
            // framework
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            // storage
            storage_path('app/settings'),
            storage_path('app/users'),
            storage_path('app/home/slides'),
            storage_path('app/news'),
            storage_path('app/schedules'),
            storage_path('app/pages'),
            storage_path('app/registration/img'),
            storage_path('app/registration/files'),
            storage_path('app/partners'),
            storage_path('app/e-shop'),
            storage_path('app/pages'),
            storage_path('app/proxy'),
            storage_path('app/photos'),
            storage_path('app/videos'),
            storage_path('app/libraries/images'),
            storage_path('app/libraries/files'),
            storage_path('app/laravel-google-analytics'),
        ];
        // we execute the verification
        $created = [];
        foreach ($to_create as $folder) {
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
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
    }
}
