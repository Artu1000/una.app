<?php

use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{
    public function run()
    {
        $partner_repo = App::make(App\Repositories\Partner\PartnerRepositoryInterface::class);

        $partner_repo->createMultiple([
            [
                'name' => 'Ville de Nantes',
                'logo' => 'img/home/logo-ville-nantes.png',
                'url' => 'http://www.nantes.fr',
                'position' => 1,
            ],
            [
                'name' => 'Université de Nantes',
                'logo' => 'img/home/logo-univ-nantes.png',
                'url' => 'http://www.univ-nantes.fr',
                'position' => 2,
            ],
            [
                'name' => 'Fédération Française d\'Aviron (FFA)',
                'logo' => 'img/home/logo-ffa.png',
                'url' => 'http://avironfrance.fr',
                'position' => 3,
            ],
            [
                'name' => 'Fédération Française des Sports Universitaires',
                'logo' => 'img/home/logo-ffsu.png',
                'url' => 'http://www.sport-u.com',
                'position' => 4
            ]
        ]);
    }
}
