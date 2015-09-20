<?php

namespace App\Http\Controllers\Front\News;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

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
        $this->loadBaseJs();
    }

    /**
     * @return $this
     */
    public function index()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Actualités';
        $this->seoMeta['meta_desc'] = 'Portes-ouvertes, stages, résultats de compétitions, événements sportifs...
        Consultez les actualités du club Université Nantes Aviron !';
        $this->seoMeta['meta_keywords'] = 'actus, actualités, club, universite, nantes, aviron, sport, universitaire,
        etudiant, ramer';

        // we get the category id
        $category = Input::get('category', null);

        // sort results by date
        $query = $this->news->orderBy('released_at', 'desc');

        // if a category is given, we filter the list
        if($category){
            $query->where('category_id', $category);
        }

        // paginate the results
        $news_list = $query->orderBy('released_at', 'desc')->paginate(10);

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news_list' => $news_list,
            'current_category' => $category,
            'css' => elixir('css/app.news.css'),
            'js' => elixir('js/app.news-list.js')
        ];

        // return the view with data
        return view('pages.front.news-list')->with($data);
    }

    public function show($news_key)
    {
        // we get the news from its unique key
        $news = $this->news->findBy('key', $news_key);

        if(!$news){
            abort(404);
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->seoMeta['meta_desc'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->seoMeta['meta_keywords'] = $news->meta_keywords;

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news' => $news,
            'css' => elixir('css/app.news.css'),
            'js' => elixir('js/app.news-detail.js')
        ];

        // return the view with data
        return view('pages.front.news-detail')->with($data);
    }

//    public function category($id)
//    {
//
//        // we verify that the given category id exist
//        if(in_array($id, config('news.categories_keys'))){
//
//
//
//            $news_list = $this->news
//                ->where('category_id', $id)
//                ->orderBy('released_at', 'desc')
////                ->paginate(10);
////            ->get();
//                ->take(10);
//                ->skip($page * 10)
//
//            $request = $request->take($limit);
//            $request = $request->skip(($page - 1) * $limit);
//
//            return response()->json([
//                'current_cat_key' => \config('news.categories.' . $id . '.key'),
//                'news_list' => $news_list
//            ], 200);
//
//        }
//
//        return response()->json([], 400);
//    }
}
