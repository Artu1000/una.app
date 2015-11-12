<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $repository;

    protected $seoMeta = [
        'page_title' => '',
        'meta_desc' => '',
        'meta_keywords' => ''
    ];

    public function __construct()
    {
        // load base JS
        \JavaScript::put([
            'base_url' => url('/'),
            'app_name' => config('settings.app_name')
        ]);

        // load modal if an alert is waiting
        if(\Session::get('alert')){
            \Javascript::put([
                'modal_alert' => true
            ]);
        }
    }
}
