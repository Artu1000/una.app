<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Repositories\Page\PageRepositoryInterface;


class PageController extends Controller
{
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(PageRepositoryInterface $page)
    {
        parent::__construct();
        $this->repository = $page;
    }

    /**
     * @return $this
     */
    public function show($page_key)
    {

        // we get the page from its page key
        $page = $this->repository->findBy('key', $page_key);

        // if no page is found, we return a 404 error
        if (!$page) {
            return abort(404);
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = strip_tags($page->title);
        $this->seoMeta['meta_desc'] = str_limit($page->meta_description);
        $this->seoMeta['meta_keywords'] = $page->meta_keywords;

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'page' => $page,
            'css' => url(elixir('css/app.page.css'))
        ];

        // return the view with data
        return view('pages.front.page')->with($data);
    }

}
