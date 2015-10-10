<?php

namespace App\Http\Controllers\EShop;

use App\Http\Controllers\Controller;
use App\Repositories\EShop\EShopArticleRepositoryInterface;
use Illuminate\Support\Facades\Input;

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

        // we get the category id
        $category = Input::get('category', null);

        // we get the two last news
        $query = $this->repository
            ->orderBy('category_id', 'asc')
            ->orderBy('price', 'asc');

        // if a category is given, we filter the list
        if($category){
            $query->where('category_id', $category);
        }

        $articles = $query->get();

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'articles' => $articles,
            'current_category' => $category,
            'css' => elixir('css/app.e-shop.css')
        ];

        // return the view with data
        return view('pages.front.e-shop')->with($data);
    }

}
