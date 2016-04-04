<?php

use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{
    public function run()
    {
        $partner_repo = app()->make(App\Repositories\Partner\PartnerRepositoryInterface::class);

        // we remove all the files in the config folder
        $files = glob(storage_path('app/partners/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/partners'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/partners');
        }

        // ville de nantes
        $slide = $partner_repo->create([
            'name'     => 'Ville de Nantes',
            'logo'     => null,
            'url'      => 'http://www.nantes.fr',
            'position' => 1,
            'active'   => true,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            database_path('seeds/files/partners/logo-ville-nantes.png'),
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
            database_path('seeds/files/partners/logo-univ-nantes.png'),
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
            database_path('seeds/files/partners/logo-ffa.png'),
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
            database_path('seeds/files/partners/logo-ffsu.png'),
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
