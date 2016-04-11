<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function index()
    {

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.dashboard.index');

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];

        // return the view with data
        return view('pages.back.dashboard')->with($data);
    }

}
