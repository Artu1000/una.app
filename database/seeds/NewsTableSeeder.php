<?php

use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        $news_repo = app(NewsRepositoryInterface::class);
        
        // we remove all the files in the config folder
        $files = glob(storage_path('app/news/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
        
        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/news'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/news');
        }

        // we insert the news page content
        $file_name = ImageManager::storeResizeAndRename(
            database_path('seeds/files/news/una_news.jpg'),
            config('image.news.background_image.name'),
            'jpg',
            config('image.news.storage_path'),
            config('image.news.background_image.sizes'),
            false
        );
        $inputs = [
            'title'            => 'Les actualités du club Université Nantes Aviron (UNA)',
            'description'      => "## Vie du club & actualités sportives\r\n\r\nA partir de cette page, suivez les actualités du club Université Nantes Aviron (UNA).\r\nRésultats sportifs, annonces d'événements, compte-rendus de déplacements ou autres annonces diverses...\r\nNe perdez pas une miette de la vie du club !",
            'background_image' => $file_name,
        ];
        file_put_contents(storage_path('app/news/content.json'), json_encode($inputs));

        // we create the news
        $news = $news_repo->create([
            'author_id'        => app(UserRepositoryInterface::class)->where('last_name', 'LORENT')->first()->id,
            'category_id'      => config('news.category_key.club'),
            'key'              => "rentree-2015-3-sessions-de-portes-ouvertes-a-l-una",
            'title'            => "Rentrée 2015 : 3 sessions de portes-ouvertes à l'UNA",
            'meta_title'       => 'Rentrée 2015 : portes-ouvertes',
            'meta_description' => null,
            'meta_keywords'    => 'club, université, nantes, aviron, portes, ouvertes, rentree, 2015, sportive',
            'released_at'      => Carbon\Carbon::createFromDate(2015, 9, 1)->hour(17)->minute(33),
            'content'          => "Vous ne savez pas quel sport choisir cette année ?  \r\nVous pensez à l'aviron sans n'avoir jamais osé vous lancer ?  \r\nLe club Université Nantes vous convie à ses portes-ouvertes, l'occasion rêvée de vous essayer gratuitement à notre sport !  \r\n## C'est quand ?  \r\nNous vous accueillons les samedis 12 septembre, 19 septembre et 10 octobre, de 10h à 17h, sans interuption. Nous misons naturellement sur des journées ensoleillées pour vous accueillir de la plus agréable des manières, cependant n'ayez crainte si quelques gouttes de pluie font leur apparition, la pratique demeure possible.  \r\n## Le programme ?  \r\nDes initiations gratuites pour toute la famille (à partir de 10 ans), encadrée par nos professionnels et bénévoles ! Vous vous familiariserez avec la planche à ramer, une embarcation pour débutants qui permet de découvrir le mouvement et les premières sensations de l'aviron. Pas de panique, la planche à ramer est (quasiment) insubmersible !  \r\n## Découvrez le club Université Nantes Aviron (UNA) !  \r\nL'UNA est LE club d'aviron universitaire de Nantes. Nous accueillons à la fois un public d'étudiants à l'Université de Nantes ou dans les écoles supérieures nantaises, mais également des plus jeunes à l'Ecole d'Aviron (entre 10 et 18 ans), ainsi que des moins jeunes (population d'actifs, quelque soit l'age ou le type de pratique).  \r\nPour en savoir plus, rendez-vous sur notre [présentation du club](../#una 'Présentation du club').  \r\n\r\nEt pour toute question, n'hésitez pas, [contactez-nous](#contact 'Contactez-nous') !  \r\nA bientôt au club Université Nantes Aviron",
            'active'           => false,
        ]);
        // we optimize and resize the news image
        $file_name = \ImageManager::storeResizeAndRename(
            database_path('seeds/files/news/news_po_una_september.jpg'),
            $news->imageName('image'),
            'jpg',
            $news->storagePath(),
            $news->availableSizes('image'),
            false
        );
        $news->image = $file_name;
        $news->save();

        // we create the news
        $news = $news_repo->create([
            'author_id'        => app(UserRepositoryInterface::class)->where('last_name', 'LORENT')->first()->id,
            'category_id'      => config('news.category_key.club'),
            'key'              => "lancement-du-nouveau-site-internet-de-l-una",
            'title'            => "Lancement du nouveau site Internet de l'UNA !",
            'meta_title'       => null,
            'meta_description' => null,
            'meta_keywords'    => 'club, université, nantes, aviron, nouveau, site, internet, web',
            'released_at'      => Carbon\Carbon::createFromDate(2016, 5, 6)->hour(10)->minute(00),
            'content'          => "## Bienvenue sur notre nouveau site Internet !\r\nNous vous souhaitons la bienvenue sur le **nouveau site Internet du club Université Nantes Aviron (UNA)**.\r\nNous sommes heureux de vous présenter notre nouvel outil, vecteur de nos futures communication et qui permettra à tous d'accéder de manière plus facile et efficace à toutes les informations concernant l'UNA.\r\n## Toutes vos informations accessibles en ligne\r\nA travers notre nouveau site, consultez régulièrement nos [actualités sportive ou nouveautés à propos de la vie du club](../../actualites), qui seront automatiquement relayées sur nos réseaux sociaux ([Facebook](https://www.facebook.com/UniversiteNantesAviron), [Twitter](https://twitter.com/unaclub), [Google+](https://plus.google.com/+Univ-nantes-avironFr)). Découvrez également l'[historique du club](../../page/historique), l'[équipe dirigeante](../../equipe-dirigeante) de notre structure, nos [statuts associatifs](../../page/statuts), ainsi que notre [règlement intérieur](../../page/reglement-interieur). Enfin, prenez connaissance de nos [tarifs d'inscription](../../inscription), consultez nos [créneaux horaires](../../horaires) et assurez vous de rester informés de nos événements grâce à notre [calendrier](../../calendrier).\r\n## L'UNA dans votre poche\r\nDe design dit \"Responsive\", ce site **s'adapte à la taille de votre terminal** et vous permet d'accéder à toutes les pages et fonctionnalités proposées depuis vos smartphones, tablettes, ordinateurs portables ou de bureau. Grâce à cela, gardez un oeil sur le club Université Nantes Aviron (UNA), que vous soyiez en déplacement ou à la maison.\r\n## Une plateforme évolutive et collaborative\r\nLoin d'être un outil figé, le site du club Université Nantes Aviron (UNA) se veut **évolutif et à l'écoute de ses utilisateurs**. Il s'agit d'un outil fait par l'UNA et pour l'UNA. C'est ainsi que les actualités, par exemple, seront régulièrement rédigées par une équipe de rédacteurs qui ne sont autres que des rameurs du club. De plus, de nouvelles fonctionnalités telles qu'une page mettant en avant le palmares de l'UNA, un nouveau système de souscription de licence en ligne, une boutique du club en ligne, ... sont autant de fonctionnalités déjà sur le feu !\r\nNous n'en disons pas plus et vous laissons découvrir par vous même notre nouveau site.\r\nVous avez une question, vous souhaitez en savoir plus sur le club ? N'hésitez pas, [contactez-nous](../../#contact) !\r\nA bientôt au club Université Nantes Aviron (UNA).",
            'active'           => true,
        ]);
        // we optimize and resize the news image
        $file_name = \ImageManager::storeResizeAndRename(
            database_path('seeds/files/news/news_una_new_website.jpg'),
            $news->imageName('image'),
            'jpg',
            $news->storagePath(),
            $news->availableSizes('image'),
            false
        );
        $news->image = $file_name;
        $news->save();
    }
}
