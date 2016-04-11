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
        $this->seo_meta['page_title'] = strip_tags($page->title);
        $this->seo_meta['meta_desc'] = $page->meta_description ? $page->meta_description : str_limit(strip_tags($page->content), 160);
        $this->seo_meta['meta_keywords'] = $page->meta_keywords;

        // og meta settings
        $this->og_meta['og:title'] = strip_tags($page->title);
        $this->og_meta['og:description'] = $page->meta_description ? $page->meta_description : str_limit(strip_tags($page->content), 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('page.show', ['key '=> $page->key]);
        $this->og_meta['og:image'] = $page->imagePath($page->image, 'image');

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'og_meta'  => $this->og_meta,
            'page'     => $page,
            'css'      => url(elixir('css/app.page.css')),
        ];

        // return the view with data
        return view('pages.front.page')->with($data);
    }

}
