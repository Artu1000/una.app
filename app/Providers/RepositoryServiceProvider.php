<?php

namespace App\Providers;

use App\Repositories\EShop\EShopArticleRepository;
use App\Repositories\News\NewsRepository;
use App\Repositories\Page\PageRepository;
use App\Repositories\Palmares\PalmaresEventRepository;
use App\Repositories\Palmares\PalmaresResultRepository;
use App\Repositories\Partner\PartnerRepository;
use App\Repositories\RegistrationPrice\RegistrationPriceRepository;
use App\Repositories\Rss\RssRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Sitemap\SitemapRepository;
use App\Repositories\User\UserRepository;
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

        // users
        $this->app->bind('App\Repositories\User\UserRepositoryInterface', function(){
            return new UserRepository();
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

        // registration prices
        $this->app->bind('App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface', function(){
            return new RegistrationPriceRepository();
        });

        // schedule
        $this->app->bind('App\Repositories\Schedule\ScheduleRepositoryInterface', function(){
            return new ScheduleRepository();
        });

        // e-shop
        $this->app->bind('App\Repositories\EShop\EShopArticleRepositoryInterface', function(){
            return new EShopArticleRepository();
        });

        // sitemap
        $this->app->bind('App\Repositories\Sitemap\SitemapRepositoryInterface', function(){
            return new SitemapRepository();
        });

        // rss
        $this->app->bind('App\Repositories\Rss\RssRepositoryInterface', function(){
            return new RssRepository();
        });
    }
}
