<?php

namespace App\Providers;

use App\Repositories\Page\PageRepository;
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
    }
}
