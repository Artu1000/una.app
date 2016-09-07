<?php

namespace App\Providers;

use App\Models\RegistrationFormDownload;
use App\Repositories\EShop\EShopArticleRepository;
use App\Repositories\EShop\EShopArticleRepositoryInterface;
use App\Repositories\Libraries\LibraryFileRepository;
use App\Repositories\Libraries\LibraryFileRepositoryInterface;
use App\Repositories\Libraries\LibraryImageRepository;
use App\Repositories\Libraries\LibraryImageRepositoryInterface;
use App\Repositories\Media\PhotoRepository;
use App\Repositories\Media\PhotoRepositoryInterface;
use App\Repositories\Media\VideoRepository;
use App\Repositories\Media\VideoRepositoryInterface;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Page\PageRepository;
use App\Repositories\Palmares\PalmaresEventRepository;
use App\Repositories\Palmares\PalmaresEventRepositoryInterface;
use App\Repositories\Palmares\PalmaresResultRepository;
use App\Repositories\Palmares\PalmaresResultRepositoryInterface;
use App\Repositories\Partner\PartnerRepository;
use App\Repositories\QrCodeScan\QrCodeScanRepository;
use App\Repositories\QrCodeScan\QrCodeScanRepositoryInterface;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
use App\Repositories\Registration\RegistrationPriceRepository;
use App\Repositories\Registration\RegistrationPriceRepositoryInterface;
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
    
        // registration form download
        $this->app->bind(RegistrationFormDownloadRepositoryInterface::class, function () {
            return new RegistrationFormDownload();
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

        // video
        $this->app->bind(VideoRepositoryInterface::class, function () {
            return new VideoRepository();
        });
    
        // image library
        $this->app->bind(LibraryImageRepositoryInterface::class, function () {
            return new LibraryImageRepository();
        });
    
        // file library
        $this->app->bind(LibraryFileRepositoryInterface::class, function () {
            return new LibraryFileRepository();
        });
        
        // qr code scans
        $this->app->bind(QrCodeScanRepositoryInterface::class, function () {
            return new QrCodeScanRepository();
        });
    }
}
