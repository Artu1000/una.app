<?php

namespace App\Repositories\Sitemap;

use App\Repositories\BaseRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Page\PageRepositoryInterface;
use App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;

class SitemapRepository extends BaseRepository implements SitemapRepositoryInterface
{
    public function __construct()
    {
        //
    }

    /**
     * @return string
     */
    public function buildSiteMap()
    {
        // we get all site pages
        $site_pages = $this->getSitePages();
        // we only take the last mod column from the site pages
        $dates = array_column($site_pages, 'last_mod');
        // we sort it
        sort($dates);
        // we get the website last modification date
        $lastmod = last($dates);
        // we get the base url from the website
        $url = route('home');

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?' . '>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml[] = '  <url>';
        $xml[] = "    <loc>$url</loc>";
        $xml[] = "    <lastmod>$lastmod</lastmod>";
        $xml[] = '    <changefreq>daily</changefreq>';
        $xml[] = '    <priority>0.8</priority>';
        $xml[] = '  </url>';

        foreach ($site_pages as $page) {
            $xml[] = "  <url>";
            $xml[] = "    <loc>{$page['url']}</loc>";
            $xml[] = "    <lastmod>{$page['last_mod']}</lastmod>";
            $xml[] = "  </url>";
        }

        $xml[] = '</urlset>';

        return join("\n", $xml);
    }

    /**
     * @return array
     */
    protected function getSitePages()
    {
        $site_pages = [];

        // news
        $site_pages[] = [
            'url'      => route('news.index'),
            'last_mod' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                app(NewsRepositoryInterface::class)
                    ->orderBy('updated_at', 'desc')
                    ->first()
                    ->updated_at
            )->format('Y-m-d'),
        ];
        $news_list = app(NewsRepositoryInterface::class)->orderBy('updated_at', 'desc')->all();
        foreach ($news_list as $news) {
            $site_pages[] = [
                'url'      => route('news.show', ['id' => $news->id, 'key' => $news->key]),
                'last_mod' => Carbon::createFromFormat('Y-m-d H:i:s', $news->updated_at)->format('Y-m-d'),
            ];
        }

        // pages
        $pages_list = app(PageRepositoryInterface::class)->orderBy('updated_at', 'desc')->all();
        foreach ($pages_list as $page) {
            $site_pages[] = [
                'url'      => route('page.show', $page->key),
                'last_mod' => Carbon::createFromFormat('Y-m-d H:i:s', $news->updated_at)->format('Y-m-d'),
            ];
        }

        // palmares
//        $site_pages[] = [
//            'url'      => route('palmares.index'),
//            'last_mod' => app(\App\Repositories\Palmares\PalmaresEventRepositoryInterface::class)
//                ->orderBy('updated_at', 'desc')
//                ->first()
//                ->updated_at,
//        ];

        // leading team
        $site_pages[] = [
            'url'      => route('front.leading_team'),
            'last_mod' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                app(UserRepositoryInterface::class)
                    ->orderBy('updated_at', 'desc')
                    ->first()
                    ->updated_at
            )->format('Y-m-d'),
        ];

        // registration
        $site_pages[] = [
            'url'      => route('registration.index'),
            'last_mod' => app(RegistrationPriceRepositoryInterface::class)
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at,
        ];

        // schedule
        $site_pages[] = [
            'url'      => route('schedules.index'),
            'last_mod' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                app(ScheduleRepositoryInterface::class)
                    ->orderBy('updated_at', 'desc')
                    ->first()
                    ->updated_at
            )->format('Y-m-d'),
        ];

        // calendar
        $site_pages[] = [
            'url'      => route('calendar.index'),
            'last_mod' => Carbon::now()->subDays(5)->format('Y-m-d'),
        ];

        // e-shop
//        $site_pages[] = [
//            'url'      => route('e-shop.index'),
//            'last_mod' => app(\App\Repositories\EShop\EShopArticleRepositoryInterface::class)
//                ->orderBy('updated_at', 'desc')
//                ->first()
//                ->updated_at,
//        ];

        return $site_pages;
    }
}