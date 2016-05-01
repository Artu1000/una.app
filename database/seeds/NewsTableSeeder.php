<?php

use App\Repositories\News\NewsRepositoryInterface;
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
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/news/una_news.jpg'),
            config('image.news.background_image.name'),
            'jpg',
            config('image.news.storage_path'),
            config('image.news.background_image.sizes'),
            false
        );
        $inputs = [
            'title'            => 'Les actualités du club Université Nantes Aviron (UNA)',
            'description'      => "### Vie du club & actualités sportives\r\n\r\nA partir de cette page, suivez les actualités du club Université Nantes Aviron (UNA).\r\nRésultats sportifs, annonces d'événements, compte-rendus de déplacements ou autres annonces diverses...\r\nNe perdez pas une miette de la vie du club !",
            'background_image' => $file_name,
        ];
        file_put_contents(storage_path('app/news/content.json'), json_encode($inputs));

        // we create the news
        $news = $news_repo->create([
            'category_id'      => config('news.category_key.club'),
            'key'              => "rentree-2015-3-sessions-de-portes-ouvertes-a-l-una",
            'title'            => "Rentrée 2015 : 3 sessions de portes-ouvertes à l'UNA",
            'image'            => 'img/news/rentree_2015_sessions_de_portes_ouvertes_a_l_una.jpg',
            'meta_title'       => 'Rentrée 2015 : portes-ouvertes',
            'meta_description' => null,
            'meta_keywords'    => 'club, université, nantes, aviron, portes, ouvertes, rentree, 2015, sportive',
            'released_at'      => \Carbon\Carbon::createFromDate(2015, 9, 1)->hour(17)->minute(33),
            'content'          => "Vous ne savez pas quel sport choisir cette année ?  \r\nVous pensez à l'aviron sans n'avoir jamais osé vous lancer ?  \r\nLe club Université Nantes vous convie à ses portes-ouvertes, l'occasion rêvée de vous essayer gratuitement à notre sport !  \r\n### C'est quand ?  \r\nNous vous accueillons les samedis 12 septembre, 19 septembre et 10 octobre, de 10h à 17h, sans interuption. Nous misons naturellement sur des journées ensoleillées pour vous accueillir de la plus agréable des manières, cependant n'ayez crainte si quelques gouttes de pluie font leur apparition, la pratique demeure possible.  \r\n### Le programme ?  \r\nDes initiations gratuites pour toute la famille (à partir de 10 ans), encadrée par nos professionnels et bénévoles ! Vous vous familiariserez avec la planche à ramer, une embarcation pour débutants qui permet de découvrir le mouvement et les premières sensations de l'aviron. Pas de panique, la planche à ramer est (quasiment) insubmersible !  \r\n### Découvrez le club Université Nantes Aviron (UNA) !  \r\nL'UNA est LE club d'aviron universitaire de Nantes. Nous accueillons à la fois un public d'étudiants à l'Université de Nantes ou dans les écoles supérieures nantaises, mais également des plus jeunes à l'Ecole d'Aviron (entre 10 et 18 ans), ainsi que des moins jeunes (population d'actifs, quelque soit l'age ou le type de pratique).  \r\nPour en savoir plus, rendez-vous sur notre [présentation du club](../#una 'Présentation du club').  \r\n\r\nEt pour toute question, n'hésitez pas, [contactez-nous](#contact 'Contactez-nous') !  \r\nA bientôt au club Université Nantes Aviron",
            'active'           => true,
        ]);

        // we optimize and resize the news image
        $file_name = \ImageManager::optimizeAndResize(
            database_path('seeds/files/news/news-po-una-september.jpg'),
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
