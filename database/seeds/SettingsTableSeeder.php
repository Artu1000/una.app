<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {

        $inputs = [
            'app_name' => 'Wonderful app',
            'multilingual' => false,
            'app_slogan' => 'A wonderful app that make miracles',
            'phone_number' => '0600000000',
            'email_contact' => 'contact@email.fr',
            'email_support' => 'support@email.fr',
            'address' => '7 rue du pain perdu',
            'zip_code' => '12345',
            'city' => 'PARADIS',
            'rss' => false,
            'loading_spinner' => '<i class="fa fa-spinner fa-spin"></i>'
        ];

        file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));
    }
}
