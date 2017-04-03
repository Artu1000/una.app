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
        'page_title'    => null,
        'meta_desc'     => null,
        'meta_keywords' => null,
        'author'        => null,
        'publisher'     => null,
        'copyright'     => null,
    ];
    
    // we set the default og meta
    protected $og_meta = [
        'og:site_name'   => null, // site name
        'og:title'       => null, // page title
        'og:description' => null, // page description
        'og:type'        => 'website', // page type : website / article
        'og:url'         => null, // page url
        'og:image'       => null, // image url
        'og:video'       => null, // video url
        'og:locale'      => null, // page locale
    ];
    
    // we set the default twitter meta
    protected $twitter_meta = [
        'twitter:card'        => 'summary', // twitter card type : summary / summary_large_image
        'twitter:site'        => '@UNAClub', // site twitter page - example : @genius
        'twitter:creator'     => '@ArthurLorent', // creator twitter page - example : @genius
        'twitter:title'       => null, // page title
        'twitter:description' => null, // page description
        'twitter:image'       => null, // link to an image
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
            'routes' => [
                'download' => route('file.download')
            ]
        ]);
        
        // we set the app developer
        $this->seo_meta['author'] = 'Arthur LORENT';
        
        // we set the publisher meta
        $this->seo_meta['publisher'] = config('settings.app_name_' . config('app.locale'));
        
        // we set the copyright meta
        $this->seo_meta['copyright'] = config('settings.app_name_' . config('app.locale'));
        
        // we set the og meta title
        $this->og_meta['og:site_name'] = config('settings.app_name_' . config('app.locale'));
        
        // we set the og locale
        $this->og_meta['og:locale'] = config('laravellocalization.supportedLocales.' . config('app.locale') . '.regional');
        
        // we set the server locale
        setlocale(LC_TIME, config('laravellocalization.supportedLocales.' . config('app.locale') . '.regional') . '.UTF-8');
        
        // we set the carbon locale
        Carbon::setLocale(config('app.locale'));
    }
}
