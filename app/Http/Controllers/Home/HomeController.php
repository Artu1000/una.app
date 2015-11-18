<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;

class HomeController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news)
    {
        parent::__construct();
        $this->repository = $news;
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Accueil';
        $this->seoMeta['meta_desc'] = 'Bienvenue sur le site du club Université Nantes Aviron,
        le plus grand club d\'aviron universitaire de France, ouvert à tous les publics !';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, ramer';

        // prepare slides data
        $slides = [];
        // UNA
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_club_300.png',
            'title' => 'Club d\'aviron à Nantes',
            'quote' => 'Nous sommes ouvert à tous les publics, (collegiens, lycéens, étudiants ou actifs), <br/>
                        Lancez-vous, venez vous initier gratuitement à notre sport !<br/>',
            'src' => url('/').'/img/home/una_bg_club.jpg',
            'bg_color' => ''
        ];
        // univ
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_universitaire_300.png',
            'title' => 'Aviron universitaire',
            'quote' => 'Rejoignez le plus grand club d\'aviron universitaire de France.<br/>
                        Nous proposons des tarifs avantageux pour tous les étudiants nantais !',
            'src' => url('/').'/img/home/una_bg_aviron_universitaire.jpg',
            'bg_color' => ''
        ];
        // competition
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_competition_300.png',
            'title' => 'Aviron en compétition',
            'quote' => 'Sport de glisse et de vitesse par excellence, <br/>
                        choisissez l\'aviron de compétition et rejoignez nos athlètes !',
            'src' => url('/').'/img/home/una_bg_aviron_competition.jpg',
            'bg_color' => ''
        ];
        // école d'aviron
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_ecole_aviron_300.png',
            'title' => 'école d\'aviron',
            'quote' => 'Nous encadrons et formons les collegiens et lycéens de 18 ans et moins.<br/>
                        Evoluez avec d\'autres jeunes et intégrez un groupe performant et dynamique !',
            'src' => url('/').'/img/home/una_bg_ecole_aviron.jpg',
            'bg_color' => ''
        ];
        // aviron féminin
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_feminin_300.png',
            'title' => 'Aviron sport féminin',
            'quote' => 'Pour la compétition ou la pratique loisir, rejoignez nos équipes 100% féminines.<br/>
                        Favorisez le développement harmonieux de vos muscles et de votre endurance !',
            'src' => url('/').'/img/home/una_bg_aviron_feminin.jpg',
            'bg_color' => ''
        ];
        // pratique loisir
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_loisir_300.png',
            'title' => 'Pratique loisir',
            'quote' => 'L\'UNA est une association sportive ouverte à la pratique loisir.<br/>
                        Ballades, détente, ... Profitez de l\'Erdre, réputée plus belle rivière de France !',
            'src' => url('/').'/img/home/una_bg_aviron_loisir.jpg',
            'bg_color' => ''
        ];

        // we get the two last news
        $last_news = $this->repository->orderBy('released_at', 'desc')->take(2)->get();

        // js data insertion
        \JavaScript::put([
            'slides_count' => sizeof($slides)
        ]);

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'slides' => $slides,
            'last_news' => $last_news,
            'css' => url(elixir('css/app.home.css')),
            'js' => url(elixir('js/app.home.js'))
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

}
