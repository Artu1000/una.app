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

    // we set the default seo meta
    protected $seo_meta = [
        'page_title'    => '',
        'meta_desc'     => '',
        'meta_keywords' => '',
    ];

    // we set the default og meta
    protected $og_meta = [
        'og:site_name'   => '',
        'og:title'       => '',
        'og:description' => '',
        'og:type'        => '',
        'og:url'         => '',
        'og:image'       => '',
        'og:video'       => '',
        'og:locale'      => '',
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
            'contact_email'   => config('settings.contact_email'),
        ]);

        // load modal if an alert is waiting
        if (session()->get('alert')) {
            JavaScript::put([
                'modal_alert' => true,
            ]);
        }

        // we set the og meta title
        $this->og_meta['og:site_name'] = config('settings.app_name_' . config('app.locale'));

        // we manage the locale dependencies
        switch (config('app.locale')) {
            case 'fr':
                // server locale
                setlocale(LC_TIME, 'fr_FR.UTF-8');
                // carbon locale
                Carbon::setLocale('fr');
                // og locale
                $this->og_meta['og:locale'] = 'fr_FR';
                break;
            case 'en':
                // server locale
                setlocale(LC_TIME, 'en_GB.UTF-8');
                // carbon locale
                Carbon::setLocale('en');
                // og locale
                $this->og_meta['og:locale'] = 'en_GB';
                break;
        }
    }
}
