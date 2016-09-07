<?php

use App\Repositories\Registration\RegistrationPriceRepositoryInterface;
use Illuminate\Database\Seeder;

class RegistrationPricesTableSeeder extends Seeder
{
    public function run()
    {
        $prices_repo = app(RegistrationPriceRepositoryInterface::class);

        // we remove all the files in the config folder
        $files = glob(storage_path('app/registration/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/registration'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/registration');
        }
    
        // we insert the schedules page content
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/registration/una_registration.jpg'),
            config('image.registration.background_image.name'),
            'jpg',
            config('image.registration.storage_path'),
            config('image.registration.background_image.sizes'),
            false
        );
        $inputs = [
            'title'            => 'S\'inscrire au club Université Nantes Aviron (UNA)',
            'description'      => "#### A noter :\r\n- Pour la **catégorie \"Tous publics\" uniquement**, une licence mi-saison est possible, valable de mai à septembre.\r\n- Pour les autres catégories, l'inscription en cours d'année ne donne droit à **aucune réduction tarifaire**, sauf opérations promotion exceptionnelles.\r\n\r\n### Pièces à joindre à votre inscription\r\n- Votre fiche d'inscription dûment remplie (autorisation parentale signée pour les mineurs).\r\n- Un certificat médical de non contre-indication à la pratique de l'aviron en compétition.\r\n- Une photo d'identité.\r\n- Votre règlement en liquide ou par chèque à l'ordre du club \"Université Nantes Aviron\".\r\n- Une photocopie de votre carte d'étudiant (étudiants uniquement).\r\n- Un brevet de natation de 50m minimum (Ecole d Aviron uniquement).\r\n\r\n### Réduction Pass Sport\r\nLe club Université Nantes Aviron accepte les Pass Sport de la saison sportive en cours (du 01/09 de l'année N au 31/08 de l'année N+1). Les Pass de la saison sportive écoulée ne sont pas admis.\r\nLes Pass de la saison en cours n'étant parfois pas encore livrés, voici le fonctionnement à suivre pour bénéficier de la réduction du Pass Sport :\r\n- Lors de son inscription à l'UNA, indiquer si le Pass Sport a été commandé.\r\n- Un seul Pass Sport est accepté par inscription.\r\n- Régler une cotisation déduite du montant du Pass Sport.\r\n- Fournir un chèque de caution du montant du Pass Sport, non-encaissé, qui sera restitué ou détruit lorsque le Pass Sport nous sera remis.\r\n\r\n### Autres informations\r\n- Il vous est possible de vous inscrire tout au long de l'année à partir du formulaire d'inscription en ligne ou directement au club.\r\n- Tout dépot de dossier d'inscription doit être effectué au début d'un créneau d'encadrement, auprès de l'équipe d'encadrement.\r\n- Vous pouvez démarrer vos entraînements dès que votre dossier d'inscription est complet et déposé.",
            'background_image' => $file_name,
        ];
        file_put_contents(storage_path('app/registration/content.json'), json_encode($inputs));

        $prices_repo->createMultiple([
            [
                'label'  => 'Carte découverte valable pour une séance',
                'price'  => 5,
                'active' => true,
            ],
            [
                'label' => 'Étudiants de l\'Université de Nantes',
                'price' => 90,
                'active' => true,
            ],
            [
                'label' => 'Étudiants autres établissements (BTS, écoles supérieures, ...)',
                'price' => 135,
                'active' => true,
            ],
            [
                'label' => 'École d\'Aviron (- de 18 ans)',
                'price' => 158,
                'active' => true,
            ],
            [
                'label' => 'Personnel de l\'Université de Nantes',
                'price' => 180,
                'active' => true,
            ],
            [
                'label' => 'Tous publics en renouvellement',
                'price' => 240,
                'active' => true,
            ],
            [
                'label' => 'Tous publics première inscription',
                'price' => 260,
                'active' => true,
            ],
            [
                'label' => 'Réduction 2ème membre de la famille (tarif tous publics uniquement)',
                'price' => -10,
                'active' => true,
            ],
            [
                'label' => 'Réduction 3ème membre de la famille (tarif tous publics uniquement)',
                'price' => -20,
                'active' => true,
            ],
        ]);
    }
}
