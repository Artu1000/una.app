<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call('PagesTableSeeder');
         $this->call('PartnersTableSeeder');
         $this->call('PalmaresTableSeeder');
         $this->call('NewsTableSeeder');
         $this->call('RolesTableSeeder');
         $this->call('UsersTableSeeder');
         $this->call('RegistrationPricesTableSeeder');
         $this->call('SchedulesTableSeeder');
         $this->call('EShopArticlesTableSeeder');
         $this->call('SettingsTableSeeder');
         $this->call('HomeTableSeeder');

        Model::reguard();

        // we give the correct rights to the storage folder after the seed
        exec('sudo chmod -R g+w ' . storage_path());
        exec('sudo chgrp -R www-data ' . storage_path());

        $this->command->info('Seed successfull');
    }
}
