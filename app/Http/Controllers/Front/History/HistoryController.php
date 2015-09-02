<?php

namespace App\Http\Controllers\Front\History;

use App\Http\Controllers\Controller;

class HistoryController extends Controller
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
        $this->seoMeta['page_title'] = 'Historique';
        $this->seoMeta['meta_desc'] = 'Découvrez l\'histoire du club Université Nantes Aviron en quelques dates ...';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, ramer, historique';

        // prepare js data
        $jsPageData = [
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css' => elixir('css/app.history.css'),
            'js' => 'home',
            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

}
