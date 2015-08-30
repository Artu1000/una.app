<?php

namespace App\Http\Controllers\Front\News;

use App\Http\Controllers\Controller;

class NewsController extends Controller
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
        $this->seoMeta['page_title'] = 'Actualités';
        $this->seoMeta['meta_desc'] = 'Portes-ouvertes, stages, résultats de compétitions, événements sportifs...
        Consultez les actualités du club Université Nantes Aviron !';
        $this->seoMeta['meta_keywords'] = 'actus, actualités, club, universite, nantes, aviron, sport, universitaire,
        etudiant, ramer';


        // prepare js data
        $jsPageData = [
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
//            'css' => elixir('css/app.news.css')
//            'js' => 'news',
//            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.news-list')->with($data);
    }

}
