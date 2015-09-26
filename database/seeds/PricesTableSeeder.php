<?php

use Illuminate\Database\Seeder;

class RegistrationPricesTableSeeder extends Seeder
{
    public function run()
    {
        $prices_repo = App::make(App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface::class);

        $prices_repo->createMultiple([
            [
                'label' => 'Carte découverte valable pour une séance',
                'price' => 5
            ],
            [
                'label' => 'Étudiants de l\'Université de Nantes',
                'price' => 90
            ],
            [
                'label' => 'Étudiants autres établissements (BTS, écoles supérieures, ...)',
                'price' => 135
            ],
            [
                'label' => 'École d\'Aviron (- de 18 ans)',
                'price' => 158
            ],
            [
                'label' => 'Personnel de l\'Université de Nantes',
                'price' => 180
            ],
            [
                'label' => 'Tous publics en renouvellement',
                'price' => 240
            ],
            [
                'label' => 'Tous publics première inscription',
                'price' => 260
            ],
            [
                'label' => 'Réduction 2ème membre de la famille (tarif tous publics uniquement)',
                'price' => -10
            ],
            [
                'label' => 'Réduction 3ème membre de la famille (tarif tous publics uniquement)',
                'price' => -20
            ],
        ]);
    }
}
