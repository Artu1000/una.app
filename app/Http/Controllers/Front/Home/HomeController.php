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
//        dd('coucou');

        // SEO Meta settings
        $this->setSeoMeta([
            'page_title' => 'Accueil',
            'description' => 'Bienvenue sur le site du club Université Nantes Aviron, le plus grand club d\'aviron
            universitaire de France, ouvert à tous les publics !',
            'keywords' => 'club, universite, nantes, aviron, sport, universitaire, etudiant, ramer'
        ]);

        // prepare data for the view
        $data = [
            'seoMeta' => $this->getSeoMeta(),
            'dependency' => 'none'
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

}
