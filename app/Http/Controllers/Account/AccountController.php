<?php

namespace App\Http\Controllers\Back\Account;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->loadBaseJs();
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Espace Membre';
        $this->seoMeta['meta_desc'] = 'Vous êtes sur l\'espace membre du club Université Nantes Aviron (UNA).';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, espace, membre';

        dd('espace membre');

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
//            'css' => elixir('css/app.home.css'),
//            'js' => elixir('js/app.home.js')
        ];

        // return the view with data
//        return view('pages.front.home')->with($data);
    }

}
