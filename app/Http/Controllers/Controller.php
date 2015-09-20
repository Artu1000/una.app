<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $seoMeta = [
        'page_title',
        'meta_desc',
        'meta_keywords'
    ];

    protected function loadBaseJs()
    {
        \JavaScript::put([
            'base_url' => url('/'),
            'site_name' => env('SITE_NAME')
        ]);
    }
}
