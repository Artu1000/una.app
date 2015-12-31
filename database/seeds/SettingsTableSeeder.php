<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        // we remove all the files in the config folder
        $files = glob(storage_path('app/config/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        // we create the folder if they doesn't exist
        if (!is_dir($storage_path = storage_path('app/config'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/config');
        }

        $logo_dark = \ImageManager::optimizeAndResize(
            './database/seeds/files/settings/logo-una-dark.png',
            'logo_dark',
            'png',
            storage_path('app/config'),
            ['admin' => [40, 40], 'header' => [70, null], 'large' => [300, null]],
            false
        );

        $logo_light = \ImageManager::optimizeAndResize(
            './database/seeds/files/settings/logo-una-light.png',
            'logo_light',
            'png',
            storage_path('app/config'),
            ['admin' => [40, 40], 'header' => [70, null], 'large' => [300, null]],
            false
        );

        // we insert base settings
        $inputs = [
            'app_name_fr'     => 'Club Université Nantes Aviron (UNA)',
            'app_name_en'     => 'Nantes University Rowing club',
            'app_slogan_fr'   => 'Le plus grand club universitaire de France.',
            'app_slogan_en'   => 'The biggest university rowing club from France.',
            'breadcrumbs'     => true,
            'multilingual'    => false,
            'phone_number'    => '0954014810',
            'contact_email'   => 'contact@una-club.fr',
            'support_email'   => 'support@una-club.fr',
            'address'         => '2 rue de la Houssinière',
            'zip_code'        => '44300',
            'city'            => 'Nantes',
            'rss'             => true,
            'loading_spinner' => '<i class="fa fa-spinner fa-spin"></i>',
            'success_icon'    => '<i class="fa fa-thumbs-up"></i>',
            'error_icon'      => '<i class="fa fa-thumbs-down"></i>',
            'info_icon'       => '<i class="fa fa-bullhorn"></i>',
            'facebook'        => 'https://www.facebook.com/UniversiteNantesAviron',
            'twitter'         => 'https://twitter.com/UNAClub',
            'google_plus'     => 'https://plus.google.com/+Univ-nantes-avironFr',
            'youtube'         => 'https://www.youtube.com/channel/UCOeYQGBxGMGqW-DyfK2fVCQ',
            'logo_dark'       => $logo_dark,
            'logo_light'      => $logo_light,
        ];
        file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));

        // we place the default favicon
        \File::copy('./database/seeds/files/settings/favicon.ico', './public/favicon.ico');

    }
}