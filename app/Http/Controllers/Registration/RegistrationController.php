<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Repositories\Palmares\PalmaresEventRepositoryInterface;
use App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface;


class RegistrationController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(RegistrationPriceRepositoryInterface $price)
    {
        parent::__construct();
        $this->repository = $price;
    }

    /**
     * @return $this
     */
    public function index()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Inscription';
        $this->seoMeta['meta_desc'] = 'Tarifs, modalités, catégories, ... Récupérez toutes les informations nécessaire
        afin de procéder à votre inscription au club Université Nantes Aviron (UNA)';
        $this->seoMeta['meta_keywords'] = 'club, université, nantes, aviron, inscription, tarif, categorie, rameur';

        $prices = $this->repository->orderBy('price', 'asc')->get();

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'prices' => $prices,
            'css' => elixir('css/app.registration.css')
        ];

        // return the view with data
        return view('pages.front.registration')->with($data);
    }

}
