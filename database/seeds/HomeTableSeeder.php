<?php

use Illuminate\Database\Seeder;

class HomeTableSeeder extends Seeder
{
    public function run()
    {
        // we create the folder if they doesn't exist
        if (!is_dir($storage_path = storage_path('app/home'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/home');
        }

        // we seed the home data
        $inputs = [
            'title'       => 'Bienvenue au club Université Nantes Aviron (UNA)',
            'description' => "Créé en 1985, le club Université Nantes Aviron (UNA) est **LE club d'aviron des étudiants nantais**.\r\n\r\nConventionné avec plusieurs écoles supérieures nantaises, l'UNA est lié à l'Université de Nantes et gère l'activité aviron au sein de la structure, en complément des autres activités sportives proposées par le SUAPS (Service Universitaire des Activités Physiques et Sportives).\r\nBasé sur les rives de l'Erdre à Nantes, à proximité du pont de la Tortière et de la Faculté de Sciences, l'UNA est aujourd'hui **le plus grand club d'aviron universitaire de France**, avec près de 600 licenciés chaque année.\r\n\r\nOrienté vers la compétition, le club est toutefois ouvert à toutes les formes de pratiques et donne aussi la possibilité de suivre des formations spécifiques à l'encadrement de l'aviron.\r\nOutre son public d'étudiants, l'UNA favorise également l'encadrement pour un public de jeunes collegiens et de lycéens, au sein de l'Ecole d'Aviron (- de 18 ans). Le club accueille parallèlement un public de seniors loisir, pratiquant l'aviron dans un objectif plus récréatif.\r\n                   \r\nA l'UNA, il est **possible de s'entrainer sans limitation de nombre de séances par semaine**. La pratique de l'aviron vous est proposée toute l'année, vacances incluses. Pour cela, l'association met à la disposition de ses membres, un parc à bateaux recensant plus de 150 coques de toutes catégories, mais aussi une salle d'ergomètres (machines à ramer) et une salle de musculation pour les compétiteurs.\r\nEtant affilié à la Fédération Française d'Aviron (FFA), l'association donne la possibilité, en plus des activités proposées par la FFSU (Fédération Française des Sports Universitaires), de participer à toutes les activités civiles de la Fédération au travers de la licence fédérale incluse dans la cotisation. C'est ainsi que le club participe chaque année à des compétitions de tous niveaux en France et à l'étranger, aussi bien dans l'aviron fédéral que face aux autres universités et établissements de l'enseignement supérieur. Il est également l'organisateur depuis 1985 des [Regataïades Internationales de Nantes](http://regataiades.fr 'Regataïades Internationales de Nantes'), reconnues comme **la plus importante régate internationale d'aviron universitaire en France**.\r\n\r\nClub d'aviron universitaire à l'ambiance sportive et chaleureuse, l'UNA se base sur le modèle de ses confrères britanniques et americains pour contribuer au **développement du sport majestueux de glisse, de vitesse et d'endurance de force qu'est l'aviron**, auprès de la population étudiante française.\r\n\r\n<span class='text - info'><i class='fa fa - exclamation - circle'></i> A noter :<span>\r\n1. Pour les universitaires, l'inscription à l'aviron doit s'effectuer **directement au club Université Nantes Aviron (UNA)** pour bénéficier de l'ensemble des créneaux d'encadrement (**ne pas s'inscrire via le SUAPS**).\r\n2. Des **tarifs préférenciels sont proposés pour tous les étudiants nantais**, sur présentation de justificatif. Des réductions plus avantageuses sont appliqués pour les membres de l'Université de Nantes (étudiants et salariés) ou d'écoles conventionnées avec l'UNA.",
            'video_link'  => 'https://youtu.be/PIUdOHcrleo',
        ];

        file_put_contents(storage_path('app/home/content.json'), json_encode($inputs));

        $slide_repo = App::make(App\Repositories\Slide\SlideRepositoryInterface::class);

        // una
        $slide = $slide_repo->create([
            'title'    => 'Club d\'aviron à Nantes',
            'quote'    => 'Nous sommes ouvert à tous les publics, (collegiens, lycéens, étudiants ou actifs).<br/> Lancez-vous et venez vous initier gratuitement à notre sport !',
            'position' => 1,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_club_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_club_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();

        // universitaires
        $slide = $slide_repo->create([
            'title'    => 'Aviron universitaire',
            'quote'    => 'Rejoignez le plus grand club d\'aviron universitaire de France.<br /> Nous proposons des tarifs avantageux pour tous les étudiants nantais !',
            'position' => 2,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_aviron_universitaire_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_aviron_universitaire_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();

        // universitaires
        $slide = $slide_repo->create([
            'title'    => 'Aviron en compétition',
            'quote'    => 'Sport de glisse et de vitesse par excellence, <br/> choisissez l\'aviron de compétition et rejoignez nos athlètes !',
            'position' => 3,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_aviron_competition_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_aviron_competition_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();

        // ecole aviron
        $slide = $slide_repo->create([
            'title'    => 'École d\'Aviron',
            'quote'    => 'Nous encadrons et formons les collegiens et lycéens de 18 ans et moins.<br/> Evoluez avec d\'autres jeunes et intégrez un groupe performant et dynamique !',
            'position' => 4,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_ecole_aviron_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_ecole_aviron_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();

        // aviron féminin
        $slide = $slide_repo->create([
            'title'    => 'Aviron sport féminin',
            'quote'    => 'Pour la compétition ou la pratique loisir, rejoignez nos équipes 100% féminines.<br/> Favorisez le développement harmonieux de vos muscles et de votre endurance !',
            'position' => 5,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_aviron_feminin_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_aviron_feminin_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();

        // pratique loisir
        $slide = $slide_repo->create([
            'title'    => 'Pratique loisir',
            'quote'    => 'L\'UNA est une association sportive ouverte à la pratique loisir.<br/> Ballades, détente, ... Profitez de l\'Erdre, réputée plus belle rivière de France !',
            'position' => 6,
        ]);
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_picto_aviron_loisir_300.png',
            $slide->imageName('picto'),
            'png',
            $slide->storagePath(),
            $slide->availableSizes('picto'),
            false
        );
        $slide->picto = $file_name;
        $slide->save();
        $file_name = \ImageManager::optimizeAndResize(
            './database/seeds/files/home/una_bg_aviron_loisir_2560.jpg',
            $slide->imageName('background_image'),
            'jpg',
            $slide->storagePath(),
            $slide->availableSizes('background_image'),
            false
        );
        $slide->background_image = $file_name;
        $slide->save();
    }

}