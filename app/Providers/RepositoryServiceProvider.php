<?php

namespace App\Providers;

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

        $this->app->bind('App\Repositories\Page\PageRepositoryInterface', function(){
            return new PageRepository();
        });

        $this->app->bind('App\Repositories\Partner\PartnerRepositoryInterface', function(){
            return new PartnerRepository();
        });

        $this->app->bind('App\Repositories\Palmares\PalmaresEventRepositoryInterface', function(){
            return new PalmaresEventRepository();
        });
        $this->app->bind('App\Repositories\Palmares\PalmaresResultRepositoryInterface', function(){
            return new PalmaresResultRepository();
        });
    }
}
