<?php

namespace App\Http\Controllers\Rss;

use App\Http\Controllers\Controller;
use App\Repositories\Rss\RssRepositoryInterface;


class RssController extends Controller
{

    private $rss;

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(RssRepositoryInterface $rss)
    {
        parent::__construct();
        $this->rss = $rss;
    }

    /**
     * @return $this
     */
    public function index()
    {
        $rss = $this->rss->buildRssFeed();

        return response($rss)->header('Content-type', 'application/rss+xml');
    }

}
