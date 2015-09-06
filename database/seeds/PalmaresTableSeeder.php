<?php

use Illuminate\Database\Seeder;

class PalmaresTableSeeder extends Seeder
{
    public function run()
    {
        $event_repo = App::make(App\Repositories\Palmares\PalmaresEventRepositoryInterface::class);
        $result_repo = App::make(App\Repositories\Palmares\PalmaresResultRepositoryInterface::class);

        $event = $event_repo->create([
            'category_id' => config('palmares.categories_keys.france-ffa-senior'),
            'location' => 'Mantes-la-Jolie (78)',
            'date' => '2015-06-06'
        ]);

        $result_repo->createMultiple([
            [
                'palmares_event_id' => $event->id,
                'boat' => 'SF2x PL',
                'position' => 17,
                'crew' => 'Solenn VERNAY, Pauline ROBIN'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'SH4+ Crit',
                'position' => 9,
                'crew' => 'Lucas FURET, Simon OLIVIER, Arthur LORENT, David ARNAUD, barreur : Florane VUILLAUME'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'SF2X Crit',
                'position' => 2,
                'crew' => 'Ainhoa ROBIN, Marianne ETIENVRE'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'SH8+',
                'position' => 10,
                'crew' => 'Jean-Bruno MOUGEL, Edouard HUEBER, Mickaël TARDY, Charles ROCHEDREUX, Brice CADIERE,
                Benjamin CORNUEL, Corentin DOBRAL, Benjamin ROUSSEAU, barreur : Camille DURAND'
            ]
        ]);

        $event = $event_repo->create([
            'category_id' => config('palmares.categories_keys.france-ffa-j16'),
            'location' => 'Libourne (33)',
            'date' => '2015-07-03'
        ]);

        $result_repo->createMultiple([
            [
                'palmares_event_id' => $event->id,
                'boat' => 'J16F2x',
                'position' => 8,
                'crew' => 'Alix TASSIN, Justine PROTT'
            ]
        ]);

        $event = $event_repo->create([
            'category_id' => config('palmares.categories_keys.france-ffsu-unss'),
            'location' => 'Bourges (18)',
            'date' => '2015-05-23'
        ]);

        $result_repo->createMultiple([
            [
                'palmares_event_id' => $event->id,
                'boat' => 'HU4x Univ Nantes',
                'position' => 1,
                'crew' => 'Benjamin DAVID, Bastien QUIQUERET, Luke EPAIN, Adrien DECRIEM'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'FU4x Univ Nantes',
                'position' => 2,
                'crew' => 'Pauline ROBIN, Marianne ETIENVRE, Ainhoa ROBIN, Solenn VERNAY'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'FU8+ Univ Nantes',
                'position' => 2,
                'crew' => 'Adele LAUWICK, Anna PIVETEAU, Hélène GERAUD, Fleur ORTIZ, Lola ROUSSEAU, Marie PICARD,
                Maïte WIPF, Auriane DION, barreur : Colin RETAILLAUD'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'M8+ Univ Nantes A',
                'position' => 3,
                'crew' => 'Thomas CERTENAIS, Brice CADIERE, Alexis CHARPENTIER SUTER, Lucas FURET, Charlotte NASSEY,
                Clarisse HESLOT, Camille DURAND, Sophie DRENOU, barreuse : Floranne VUILLAUME'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'M8+ Univ Nantes B',
                'position' => 6,
                'crew' => ''
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'HU2x Univ Nantes',
                'position' => 7,
                'crew' => 'Charles ROCHEDREUX, Jean-Bruno MOUGEL'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'HU2x Centrale Nantes',
                'position' => 2,
                'crew' => 'Corentin DOBRAL'
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'FU4x Centrale Nantes',
                'position' => 11,
                'crew' => ''
            ],
            [
                'palmares_event_id' => $event->id,
                'boat' => 'HU8+ Centrale Nantes',
                'position' => 12,
                'crew' => ''
            ],
        ]);
    }
}
