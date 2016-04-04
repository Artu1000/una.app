<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use JavaScript;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $repository;

    protected $seoMeta = [
        'page_title'    => '',
        'meta_desc'     => '',
        'meta_keywords' => '',
    ];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // load base JS
        JavaScript::put([
            'csrf_token'      => csrf_token(),
            'base_url'        => url('/'),
            'app_name'        => config('settings.app_name_' . config('app.locale')),
            'loading_spinner' => config('settings.loading_spinner'),
            'success_icon'    => config('settings.success_icon'),
            'error_icon'      => config('settings.error_icon'),
            'info_icon'       => config('settings.info_icon'),
            'locale'          => config('app.locale'),
            'multilingual'    => config('settings.multilingual'),
        ]);

        // load modal if an alert is waiting
        if (session()->get('alert')) {
            Javascript::put([
                'modal_alert' => true,
            ]);
        }

        // we manage the Carbon locale
        switch (config('app.locale')) {
            case 'fr':
                setlocale(LC_TIME, 'fr_FR.UTF-8');
                Carbon::setLocale('fr');
                break;
            case 'en':
                setlocale(LC_TIME, 'en_GB.UTF-8');
                Carbon::setLocale('en');
                break;
        }
    }
}
