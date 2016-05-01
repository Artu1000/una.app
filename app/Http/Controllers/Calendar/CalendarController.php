<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
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
        $this->seo_meta['page_title'] = trans('seo.front.calendar.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.calendar.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.calendar.keywords');

        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.calendar.title');
        $this->og_meta['og:description'] = trans('seo.front.calendar.description');
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('calendar.index');

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'og_meta'  => $this->og_meta,
            'css'      => elixir('css/app.calendar.css'),
        ];

        // return the view with data
        return view('pages.front.calendar')->with($data);
    }

}
