<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Slide\SlideRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private $news;
    private $slide;

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news, SlideRepositoryInterface $slide)
    {
        parent::__construct();
        $this->news = $news;
        $this->slide = $slide;
    }

    public function edit(Request $request)
    {
        // we check the current user permission
        $required = 'home.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.home.index');

        // we define the slides table list columns
        $columns = [
            [
                'title' => trans('home.page.label.slide.background_image'),
                'key'   => 'background_image',
                'image' => [
                    'storage_path' => $this->slide->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => '767',
                    ],
                ],
            ], [
                'title' => trans('home.page.label.slide.picto'),
                'key'   => 'picto',
                'image' => [
                    'storage_path' => $this->slide->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'picto',
                    ],
                ],
            ], [
                'title' => trans('home.page.label.slide.title'),
                'key'   => 'title',
            ], [
                'title' => trans('home.page.label.slide.quote'),
                'key'   => 'quote',
            ], [
                'title'   => trans('home.page.label.slide.position'),
                'key'     => 'position',
            ],
        ];

        // we instantiate the query
        $query = $this->slide->getModel()->query();

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('home.page.action.delete'),
            'attributes' => ['title'],
        ];

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData(
            $query,
            $request,
            $columns,
            'slides',
            $confirm_config
        );

        // we get the json home content
        $home = [];
        if (is_file(storage_path('app/home/home.json'))) {
            $home = json_decode(file_get_contents(storage_path('app/home/home.json')));
        }

        // prepare data for the view
        $data = [
            'seoMeta'         => $this->seoMeta,
            'news_activation' => (isset($home['news_activation']) && !empty($home['news_activation'])) ? $home['news_activation'] : null,
            'description'     => (isset($home['description']) && !empty($home['description'])) ? $home['description'] : null,
            'tableListData' => $tableListData,
        ];

        // return the view with data
        return view('pages.back.home-edit')->with($data);
    }

    /**
     * @return $this
     */
    public function show()
    {

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Accueil';
        $this->seoMeta['meta_desc'] = 'Bienvenue sur le site du club Université Nantes Aviron,
        le plus grand club d\'aviron universitaire de France, ouvert à tous les publics !';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, ramer';

//        // prepare slides data
//        $slides = [];
//        // UNA
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_club_300.png',
//            'title' => 'Club d\'aviron à Nantes',
//            'quote' => 'Nous sommes ouvert à tous les publics, (collegiens, lycéens, étudiants ou actifs), <br/>
//                        Lancez-vous, venez vous initier gratuitement à notre sport !<br/>',
//            'src' => url('/').'/img/home/una_bg_club.jpg',
//            'bg_color' => ''
//        ];
//        // univ
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_aviron_universitaire_300.png',
//            'title' => 'Aviron universitaire',
//            'quote' => 'Rejoignez le plus grand club d\'aviron universitaire de France.<br/>
//                        Nous proposons des tarifs avantageux pour tous les étudiants nantais !',
//            'src' => url('/').'/img/home/una_bg_aviron_universitaire.jpg',
//            'bg_color' => ''
//        ];
//        // competition
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_aviron_competition_300.png',
//            'title' => 'Aviron en compétition',
//            'quote' => 'Sport de glisse et de vitesse par excellence, <br/>
//                        choisissez l\'aviron de compétition et rejoignez nos athlètes !',
//            'src' => url('/').'/img/home/una_bg_aviron_competition.jpg',
//            'bg_color' => ''
//        ];
//        // école d'aviron
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_ecole_aviron_300.png',
//            'title' => 'école d\'aviron',
//            'quote' => 'Nous encadrons et formons les collegiens et lycéens de 18 ans et moins.<br/>
//                        Evoluez avec d\'autres jeunes et intégrez un groupe performant et dynamique !',
//            'src' => url('/').'/img/home/una_bg_ecole_aviron.jpg',
//            'bg_color' => ''
//        ];
//        // aviron féminin
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_aviron_feminin_300.png',
//            'title' => 'Aviron sport féminin',
//            'quote' => 'Pour la compétition ou la pratique loisir, rejoignez nos équipes 100% féminines.<br/>
//                        Favorisez le développement harmonieux de vos muscles et de votre endurance !',
//            'src' => url('/').'/img/home/una_bg_aviron_feminin.jpg',
//            'bg_color' => ''
//        ];
//        // pratique loisir
//        $slides[] = [
//            'picto' => url('/').'/img/home/una_picto_aviron_loisir_300.png',
//            'title' => 'Pratique loisir',
//            'quote' => 'L\'UNA est une association sportive ouverte à la pratique loisir.<br/>
//                        Ballades, détente, ... Profitez de l\'Erdre, réputée plus belle rivière de France !',
//            'src' => url('/').'/img/home/una_bg_aviron_loisir.jpg',
//            'bg_color' => ''
//        ];

        $slides = $this->slide->orderBy('position', 'asc')->get();

        // we get the two last news
        $last_news = $this->news->orderBy('released_at', 'desc')->take(2)->get();

        // js data insertion
        \JavaScript::put([
            'slides_count' => sizeof($slides),
        ]);

        // prepare data for the view
        $data = [
            'seoMeta'   => $this->seoMeta,
            'slides'    => $slides,
            'last_news' => $last_news,
            'css'       => url(elixir('css/app.home.css')),
            'js'        => url(elixir('js/app.home.js')),
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

}
