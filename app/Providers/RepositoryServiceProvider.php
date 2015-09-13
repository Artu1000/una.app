<?php

namespace App\Providers;

use App\Repositories\News\NewsRepository;
use App\Repositories\Page\PageRepository;
use App\Repositories\Palmares\PalmaresEventRepository;
use App\Repositories\Palmares\PalmaresResultRepository;
use App\Repositories\Partner\PartnerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // pages
        $this->app->bind('App\Repositories\Page\PageRepositoryInterface', function(){
            return new PageRepository();
        });

        // partners
        $this->app->bind('App\Repositories\Partner\PartnerRepositoryInterface', function(){
            return new PartnerRepository();
        });

        // palmares
        $this->app->bind('App\Repositories\Palmares\PalmaresEventRepositoryInterface', function(){
            return new PalmaresEventRepository();
        });
        $this->app->bind('App\Repositories\Palmares\PalmaresResultRepositoryInterface', function(){
            return new PalmaresResultRepository();
        });

        // news
        $this->app->bind('App\Repositories\News\NewsRepositoryInterface', function(){
            return new NewsRepository();
        });
    }
}
