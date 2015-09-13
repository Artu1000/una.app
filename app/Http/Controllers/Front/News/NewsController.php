<?php

namespace App\Http\Controllers\Front\News;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;

class NewsController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news)
    {
        $this->news = $news;
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

        $news_list = $this->news->orderBy('released_at', 'desc')->paginate(10);

        // prepare js data
        $jsPageData = [
            //
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news_list' => $news_list,
            'css' => elixir('css/app.news.css')
//            'js' => 'news',
//            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.news-list')->with($data);
    }

    public function show($id)
    {

        $news = $this->news->find($id);

        // SEO Meta settings
        $this->seoMeta['page_title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->seoMeta['meta_desc'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->seoMeta['meta_keywords'] = $news->meta_keywords;

        // prepare js data
        $jsPageData = [
            //
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news' => $news,
            'css' => elixir('css/app.news.css')
//            'js' => 'news',
//            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.news-detail')->with($data);
    }

}
