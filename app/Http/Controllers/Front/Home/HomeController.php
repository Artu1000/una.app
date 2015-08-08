<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {

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
            'picto' => url('/').'/img/home/una_picto_logo_club.png',
            'title' => 'Club d\'aviron à Nantes',
            'quote' => 'Nous sommes ouvert à tous les publics, (collegiens, lycéens, étudiants ou actifs), <br/>
                        Lancez-vous, venez vous initier gratuitement à notre sport !<br/>',
            'src' => url('/').'/img/home/una_bg_club.jpg',
            'bg_color' => ''
        ];
        // univ
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_logo_club.png',
            'title' => 'Aviron universitaire',
            'quote' => 'Rejoignez le plus grand club d\'aviron universitaire de France.<br/>
                        Nous proposons des tarifs avantageux pour tous les étudiants nantais !',
            'src' => url('/').'/img/home/una_bg_aviron_universitaire.jpg',
            'bg_color' => ''
        ];
        // competition
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_competition.png',
            'title' => 'Aviron en compétition',
            'quote' => 'Sport de glisse et de vitesse par excellence, <br/>
                        choisissez l\'aviron de compétition et rejoignez nos athlètes !',
            'src' => url('/').'/img/home/una_bg_aviron_competition.jpg',
            'bg_color' => ''
        ];
        // école d'aviron
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_ecole_aviron.png',
            'title' => 'école d\'aviron',
            'quote' => 'Nous encadrons et formons les collegiens et lycéens de 18 ans et moins.<br/>
                        Evoluez avec d\'autres jeunes et intégrez un groupe performant et dynamique !',
            'src' => url('/').'/img/home/una_bg_ecole_aviron.jpg',
            'bg_color' => ''
        ];
        // aviron féminin
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_feminin.png',
            'title' => 'Aviron sport féminin',
            'quote' => 'Pour la compétition ou la pratique loisir, rejoignez nos équipes 100% féminines.<br/>
                        Favorisez le développement harmonieux de vos muscles et de votre endurance !',
            'src' => url('/').'/img/home/una_bg_aviron_feminin.jpg',
            'bg_color' => ''
        ];
        // pratique loisir
        $slides[] = [
            'picto' => url('/').'/img/home/una_picto_aviron_loisir.png',
            'title' => 'Pratique loisir',
            'quote' => 'L\'UNA est une association sportive ouverte à la pratique loisir.<br/>
                        Ballades, détente, ... Profitez de l\'Erdre, réputée plus belle rivière de France !',
            'src' => url('/').'/img/home/una_bg_aviron_loisir.jpg',
            'bg_color' => ''
        ];



        $lastNews = [];
        $lastNews[] = [
            'title' => 'News 1',
            'src' => 'img1',
            'content' => '<p>Ultima Syriarum est Palaestina per intervalla magna protenta, cultis abundans terris et nitidis et civitates habens quasdam egregias, nullam nulli cedentem sed sibi vicissim velut ad perpendiculum aemulas: Caesaream, quam ad honorem Octaviani principis exaedificavit Herodes, et Eleutheropolim et Neapolim itidemque Ascalonem Gazam aevo superiore exstructas.</p>
            <p>Fuerit toto in consulatu sine provincia, cui fuerit, antequam designatus est, decreta provincia. Sortietur an non? Nam et non sortiri absurdum est, et, quod sortitus sis, non habere. Proficiscetur paludatus? Quo? Quo pervenire ante certam diem non licebit. ianuario, Februario, provinciam non habebit; Kalendis ei denique Martiis nascetur repente provincia.</p>
            <p>Nihil morati post haec militares avidi saepe turbarum adorti sunt Montium primum, qui divertebat in proximo, levi corpore senem atque morbosum, et hirsutis resticulis cruribus eius innexis divaricaturn sine spiramento ullo ad usque praetorium traxere praefecti.</p>
            <p>Oportunum est, ut arbitror, explanare nunc causam, quae ad exitium praecipitem Aginatium inpulit iam inde a priscis maioribus nobilem, ut locuta est pertinacior fama. nec enim super hoc ulla documentorum rata est fides.</p>
            <p>Orientis vero limes in longum protentus et rectum ab Euphratis fluminis ripis ad usque supercilia porrigitur Nili, laeva Saracenis conterminans gentibus, dextra pelagi fragoribus patens, quam plagam Nicator Seleucus occupatam auxit magnum in modum, cum post Alexandri Macedonis obitum successorio iure teneret regna Persidis, efficaciae inpetrabilis rex, ut indicat cognomentum.</p>'
        ];
        $lastNews[] = [
            'title' => 'News 2',
            'src' => 'img2',
            'content' => '<p>Ultima Syriarum est Palaestina per intervalla magna protenta, cultis abundans terris et nitidis et civitates habens quasdam egregias, nullam nulli cedentem sed sibi vicissim velut ad perpendiculum aemulas: Caesaream, quam ad honorem Octaviani principis exaedificavit Herodes, et Eleutheropolim et Neapolim itidemque Ascalonem Gazam aevo superiore exstructas.</p>
            <p>Fuerit toto in consulatu sine provincia, cui fuerit, antequam designatus est, decreta provincia. Sortietur an non? Nam et non sortiri absurdum est, et, quod sortitus sis, non habere. Proficiscetur paludatus? Quo? Quo pervenire ante certam diem non licebit. ianuario, Februario, provinciam non habebit; Kalendis ei denique Martiis nascetur repente provincia.</p>
            <p>Nihil morati post haec militares avidi saepe turbarum adorti sunt Montium primum, qui divertebat in proximo, levi corpore senem atque morbosum, et hirsutis resticulis cruribus eius innexis divaricaturn sine spiramento ullo ad usque praetorium traxere praefecti.</p>
            <p>Oportunum est, ut arbitror, explanare nunc causam, quae ad exitium praecipitem Aginatium inpulit iam inde a priscis maioribus nobilem, ut locuta est pertinacior fama. nec enim super hoc ulla documentorum rata est fides.</p>
            <p>Orientis vero limes in longum protentus et rectum ab Euphratis fluminis ripis ad usque supercilia porrigitur Nili, laeva Saracenis conterminans gentibus, dextra pelagi fragoribus patens, quam plagam Nicator Seleucus occupatam auxit magnum in modum, cum post Alexandri Macedonis obitum successorio iure teneret regna Persidis, efficaciae inpetrabilis rex, ut indicat cognomentum.</p>'
        ];



        $partners = [];
        $partners[] = [
            'title' => 'Ville de Nantes',
            'url' => 'http://www.nantes.fr',
            'src' => url('/').'/img/home/logo-ville-nantes.png'
        ];
        $partners[] = [
            'title' => 'Université de Nantes',
            'url' => 'http://www.univ-nantes.fr',
            'src' => url('/').'/img/home/logo-univ-nantes.png'
        ];
        $partners[] = [
            'title' => 'Fédération Française d\'Aviron',
            'url' => 'http://avironfrance.fr',
            'src' => url('/').'/img/home/logo-ffa.png'
        ];
        $partners[] = [
            'title' => 'Fédération Française des Sports Universitaires',
            'url' => 'http://www.sport-u.com',
            'src' => url('/').'/img/home/logo-ffsu.png'
        ];

        // prepare js data
        $jsPageData = [
            'slides_count' => sizeof($slides)
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'slides' => $slides,
            'lastNews' => $lastNews,
            'partners' => $partners,
            'css' => elixir('css/app.home.css'),
            'js' => 'home',
            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

}
