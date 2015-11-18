<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {

        $inputs = [
            'app_name' => 'Wonderful app',
            'phone_number' => '0000000000',
            'email_contact' => 'contact@email.fr',
            'email_support' => 'support@email.fr',
            'address',
            'zip_code',
            'city',
            'zip_code' => 44000,
            'facebook' => '',
            'twitter' => '',
            'google_plus' => '',
            'youtube' => '',
            'rss' => false,
            'loading_spinner' => '<i class="fa fa-spinner fa-spin"></i>',
            'google_analytics' => ''
        ];

        file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));
    }
}
