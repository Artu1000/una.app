<?php

namespace App\Providers;

use App\Repositories\EShop\EShopArticleRepository;
use App\Repositories\EShop\EShopArticleRepositoryInterface;
use App\Repositories\Media\PhotoRepository;
use App\Repositories\Media\PhotoRepositoryInterface;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Page\PageRepository;
use App\Repositories\Palmares\PalmaresEventRepository;
use App\Repositories\Palmares\PalmaresEventRepositoryInterface;
use App\Repositories\Palmares\PalmaresResultRepository;
use App\Repositories\Palmares\PalmaresResultRepositoryInterface;
use App\Repositories\Partner\PartnerRepository;
use App\Repositories\RegistrationPrice\RegistrationPriceRepository;
use App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Rss\RssRepository;
use App\Repositories\Rss\RssRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Sitemap\SitemapRepository;
use App\Repositories\Sitemap\SitemapRepositoryInterface;
use App\Repositories\Slide\SlideRepository;
use App\Repositories\Slide\SlideRepositoryInterface;
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
        $this->app->bind('App\Repositories\Page\PageRepositoryInterface', function () {
            return new PageRepository();
        });

        // partners
        $this->app->bind('App\Repositories\Partner\PartnerRepositoryInterface', function () {
            return new PartnerRepository();
        });

        // users
        $this->app->bind('App\Repositories\User\UserRepositoryInterface', function () {
            return new UserRepository();
        });

        // roles
        $this->app->bind('App\Repositories\Roles\RoleRepositoryInterface', function () {
            return new RoleRepository();
        });

        // palmares
        $this->app->bind(PalmaresEventRepositoryInterface::class, function () {
            return new PalmaresEventRepository();
        });
        $this->app->bind(PalmaresResultRepositoryInterface::class, function () {
            return new PalmaresResultRepository();
        });

        // news
        $this->app->bind(NewsRepositoryInterface::class, function () {
            return new NewsRepository();
        });

        // registration prices
        $this->app->bind(RegistrationPriceRepositoryInterface::class, function () {
            return new RegistrationPriceRepository();
        });

        // schedule
        $this->app->bind(ScheduleRepositoryInterface::class, function () {
            return new ScheduleRepository();
        });

        // e-shop
        $this->app->bind(EShopArticleRepositoryInterface::class, function () {
            return new EShopArticleRepository();
        });

        // sitemap
        $this->app->bind(SitemapRepositoryInterface::class, function () {
            return new SitemapRepository();
        });

        // rss
        $this->app->bind(RssRepositoryInterface::class, function () {
            return new RssRepository();
        });

        // slideshow
        $this->app->bind(SlideRepositoryInterface::class, function () {
            return new SlideRepository();
        });

        // photo
        $this->app->bind(PhotoRepositoryInterface::class, function () {
            return new PhotoRepository();
        });
    }
}
