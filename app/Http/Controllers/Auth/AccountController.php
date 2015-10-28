<?php

namespace App\Http\Controllers\Auth;

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
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Créer un compte';
        $this->seoMeta['meta_desc'] = 'Créez votre compte UNA et accédez à nos services en ligne.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, creer, creation, compte';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css' => elixir('css/app.login.css')
        ];

        // return the view with data
        return view('pages.front.account-creation')->with($data);
    }

}
