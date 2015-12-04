<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        // we remove all the files in the config folder
        $files = glob(storage_path('app/config/*'));
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        $inputs = [
            'app_name'        => 'Wonderful app',
            'app_slogan'      => 'A brilliant app that make miracles',
            'breadcrumbs'     => true,
            'multilingual'    => false,
            'phone_number'    => '0699999999',
            'contact_email'   => 'contact@email.fr',
            'support_email'   => 'support@email.fr',
            'address'         => '7 rue du pain perdu',
            'zip_code'        => '12345',
            'city'            => 'NANTES',
            'rss'             => false,
            'loading_spinner' => '<i class="fa fa-spinner fa-cog"></i>',
        ];

        file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));
    }
}