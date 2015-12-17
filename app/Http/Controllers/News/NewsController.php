<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class NewsController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news)
    {
        parent::__construct();
        $this->repository = $news;
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
        $query = $this->repository->orderBy('released_at', 'desc');

        // if a category is given, we filter the list
        if ($category) {
            $query->where('category_id', $category);
        }

        // paginate the results
        $news_list = $query->paginate(10);

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'news_list'        => $news_list,
            'current_category' => $category,
            'css'              => url(elixir('css/app.news.css')),
            'js'               => url(elixir('js/app.news-list.js')),
        ];

        // return the view with data
        return view('pages.front.news-list')->with($data);
    }

    public function adminList(Request $request)
    {
        // we check the current user permission
        $required = 'news.list';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.news.list');

        // we define the table list columns
        $columns = [
            [
                'title' => trans('news.page.label.image'),
                'key'   => 'logo',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => '767',
                    ],
                ],
            ],
            [
                'title'   => trans('news.page.label.title'),
                'key'     => 'title',
                'sort_by' => 'news.title',
            ],
            [
                'title'     => trans('news.page.label.content'),
                'key'       => 'content',
                'str_limit' => 150,
            ],
            [
                'title'           => trans('news.page.label.released_at'),
                'key'             => 'released_at',
                'sort_by'         => 'news.released_at',
                'sort_by_default' => true,
                'date'            => 'd/m/Y H:i',
            ],
            [
                'title'    => trans('news.page.label.activation'),
                'key'      => 'active',
                'activate' => 'news.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'news.list',
            'create'  => 'news.create',
            'edit'    => 'news.edit',
            'destroy' => 'news.destroy',
        ];

        // we instantiate the query
        $query = $this->repository->getModel()->query();

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('news.page.action.delete'),
            'attributes' => ['title'],
        ];

        // we prepare the search config
        $search_config = [
            'title',
        ];

        // we enable the lines choice
        $enable_lines_choice = true;

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData(
            $query,
            $request,
            $columns,
            $routes,
            $confirm_config,
            $search_config,
            $enable_lines_choice
        );

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.news-list')->with($data);
    }

    public function show($news_key)
    {
        // we get the news from its unique key
        $news = $this->repository->findBy('key', $news_key);

        if (!$news) {
            abort(404);
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->seoMeta['meta_desc'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->seoMeta['meta_keywords'] = $news->meta_keywords;

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news'    => $news,
            'css'     => url(elixir('css/app.news.css')),
            'js'      => url(elixir('js/app.news-detail.js')),
        ];

        // return the view with data
        return view('pages.front.news-detail')->with($data);
    }
}
