<?php

namespace App\Http\Controllers\Sitemap;

use App\Http\Controllers\Controller;
use App\Repositories\Sitemap\SitemapRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller

{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(SitemapRepositoryInterface $sitemap)
    {
        $this->loadBaseJs();
        $this->repository = $sitemap;
    }

    /**
     * @return $this
     */
    public function index(){
        $site_map = $this->repository->buildSiteMap();
        return response($site_map)->header('Content-type', 'text/xml');
    }

}
