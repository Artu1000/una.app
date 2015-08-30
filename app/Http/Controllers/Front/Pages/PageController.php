<?php

namespace App\Http\Controllers\Front\Pages;

use App\Http\Controllers\Controller;
use App\Repositories\Page\PageRepositoryInterface;


class PageController extends Controller
{

    private $page;

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(PageRepositoryInterface $page)
    {
        $this->page = $page;
    }

    /**
     * @return $this
     */
    public function index($page_key)
    {

        // we get the page from its page key
        $page = $this->page->findBy('page_key', $page_key);

        // if no page is found, we return a 404 error
        if (!$page) {
            return abort(404);
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = strip_tags($page->title);
        $this->seoMeta['meta_desc'] = str_limit(strip_tags($page->content), 150);
        $this->seoMeta['meta_keywords'] = $page->meta_keywords;

        // prepare js data
        $jsPageData = [
        ];

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'page' => $page,
            'css' => elixir('css/app.page.css'),
//            'js' => 'pages',
//            'jsPageData' => $jsPageData
        ];

        // return the view with data
        return view('pages.front.page')->with($data);
    }

}
