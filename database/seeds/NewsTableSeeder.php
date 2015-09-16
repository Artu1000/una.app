<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        $news_repo = App::make(App\Repositories\News\NewsRepositoryInterface::class);

        $news_repo->createMultiple([
            [
                'news_category_id' => config('news.categories_keys.club'),
                'title' => "Rentrée 2015 : 3 sessions de portes-ouvertes à l'UNA",
                'meta_title' => 'Rentrée 2015 : portes-ouvertes',
                'meta_description' => '',
                'meta_keywords' => 'club, université, nantes, aviron, portes, ouvertes, rentree, 2015, sportive',
                'released_at' => '2015-09-07 21:30:00',
                'image' => 'img/news/rentree_2015_sessions_de_portes_ouvertes_a_l_una.jpg',
                'content' => "<p>Vous ne savez pas quel sport choisir cette année ?<br/>
                                Vous pensez à l'aviron sans n'avoir jamais osé vous lancer ?<br/>
                                Le club Université Nantes vous convie à ses portes-ouvertes, l'occasion rêvée de vous essayer gratuitement à notre sport !</p>
                                <p>&nbsp;</p>

                                <h3>C'est quand ?</h3>
                                <p>Nous vous accueillons les samedis 12 septembre, 19 septembre et 10 octobre, de 10h à 17h.</p>
                                <p>&nbsp;</p>

                                <h3>Le programme ?</h3>
                                <p>Des initiations gratuites pour toute la famille (à partir de 10 ans), encadrée par nos professionnels et bénévoles !</p>
                                <p>&nbsp;</p>

                                <h3>Qu'est-ce que le club Université Nantes Aviron (UNA) ?</h3>
                                <p>L'UNA est LE club d'aviron universitaire de Nantes. Nous accueillons à la fois un public d'étudiants à l'Université de Nantes ou dans les écoles supérieures nantaises, mais également des plus jeunes à l'Ecole d'Aviron (entre 10 et 18 ans), ainsi que des moins jeunes (population d'actifs, quelque soit l'age ou le type de pratique). Pour en savoir plus, rendez-vous sur notre <a href=\"{{ url('/') }}/#una-club\">présentation du club</a>.</p>
                                <p>&nbsp;</p>

                                <p>Et pour toute question, n'hésitez pas, <a href=\"#contact\" title=\"Nous contacter\">contactez-nous !</a><br/>
                                A bientôt au club Université Nantes Aviron</p>"
            ]
        ]);
    }
}
