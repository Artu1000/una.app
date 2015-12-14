<?php

use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{
    public function run()
    {
        $partner_repo = app()->make(App\Repositories\Partner\PartnerRepositoryInterface::class);

        // ville de nantes
        $slide = $partner_repo->create([
            'name'     => 'Ville de Nantes',
            'logo'     => null,
            'url'      => 'http://www.nantes.fr',
            'position' => 1,
            'active'   => true,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/partners/logo-ville-nantes.png',
            $slide->imageName('logo'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('logo'),
            false
        );
        $slide->logo = $file_name;
        $slide->save();

        // université de nantes
        $slide = $partner_repo->create([
            'name'     => 'Université de Nantes',
            'logo'     => null,
            'url'      => 'http://www.univ-nantes.fr',
            'position' => 2,
            'active'   => true,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/partners/logo-univ-nantes.png',
            $slide->imageName('logo'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('logo'),
            false
        );
        $slide->logo = $file_name;
        $slide->save();

        // ffa
        $slide = $partner_repo->create([
            'name'     => 'Fédération Française d\'Aviron (FFA)',
            'logo'     => null,
            'url'      => 'http://avironfrance.fr',
            'position' => 3,
            'active'   => true,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/partners/logo-ffa.png',
            $slide->imageName('logo'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('logo'),
            false
        );
        $slide->logo = $file_name;
        $slide->save();

        // ffsu
        $slide = $partner_repo->create([
            'name'     => 'Fédération Française des Sports Universitaires',
            'logo'     => null,
            'url'      => 'http://www.sport-u.com',
            'position' => 4,
            'active'   => true,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/partners/logo-ffsu.png',
            $slide->imageName('logo'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('logo'),
            false
        );
        $slide->logo = $file_name;
        $slide->save();

    }
}
