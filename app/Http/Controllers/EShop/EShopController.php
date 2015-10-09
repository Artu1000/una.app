<?php

namespace App\Http\Controllers\EShop;

use App\Http\Controllers\Controller;
use App\Repositories\EShop\EShopArticleRepositoryInterface;

class EShopController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(EShopArticleRepositoryInterface $e_shop_article)
    {
        $this->repository = $e_shop_article;
        $this->loadBaseJs();
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Boutique en ligne';
        $this->seoMeta['meta_desc'] = 'Découvrez tous les articles du club Université Nantes Aviron (UNA)
        proposés à la vente.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant,
        boutique, ligne, shopping';

        // we get the two last news
        $articles = $this->repository
            ->orderBy('category_id', 'asc')
            ->orderBy('price', 'asc')
            ->get();
//            ->paginate(10);

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'articles' => $articles,
            'css' => elixir('css/app.e-shop.css')
//            'js' => elixir('js/app.home.js')
        ];

        // return the view with data
        return view('pages.front.e-shop')->with($data);
    }

}
