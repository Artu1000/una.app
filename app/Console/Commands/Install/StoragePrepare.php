<?php

namespace App\Console\Commands\Install;

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
        $this->line(' ');

        $this->line('Preparing storage folders ...');

        // we set the folder to verify
        $to_create = [
            storage_path(),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/meta'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('app'),
            storage_path('app/config')
        ];
        // we execute the verification
        $created = [];
        foreach($to_create as $folder){
            if(!is_dir($folder)){
                mkdir($folder);
                $created[] = $folder;
            }
        }

        if(!empty($created)){
            $this->info('✔ Storage folder prepared. Folders created :');
            foreach($created as $folder){
                $this->info('- ' . $folder);
            }
        } else {
            $this->info('✔ No folder are missing');
        }

        $this->line(' ');

        // settings.json existence verification
        if (!is_file(storage_path('app/config/settings.json'))) {
            if (file_put_contents(storage_path('app/config/settings.json'), '{}')) {
                $this->info('✔ File "' . storage_path('app/config/settings.json') . '" has not been found. The file has been created');
                exec('php artisan db:seed --class=SettingsTableSeeder');
            } else {
                $this->error('File "storage/framework/config/settings.json" has not been found and a problem occurend while we tried to create it');
            };
            $this->line(' ');
        }
    }
}
