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

        Model::reguard();

        $this->command->info('Seed successfull');
    }
}
