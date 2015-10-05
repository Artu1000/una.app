<?php

namespace App\Repositories\Sitemap;

use App\Repositories\BaseRepository;

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
        // we only take the last mod colmun from the site pages
        $dates = array_column($site_pages, 'last_mod');
        // we sort it
        sort($dates);
        // we get the website last modification date
        $lastmod = last($dates);
        // we get the base url from the website
        $url = trim(url(), '/') . '/';

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?'.'>';
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
            'url' => route('front.news'),
            'last_mod' => \App::make('App\Repositories\News\NewsRepositoryInterface')
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at
        ];
        $news_list = \App::make('App\Repositories\News\NewsRepositoryInterface')->orderBy('updated_at', 'desc')->all();
        foreach($news_list as $news){
            $site_pages[] = [
                'url' => route('front.news.detail', $news->key),
                'last_mod' => $news->updated_at
            ];
        }

        // pages
        $pages_list = \App::make('App\Repositories\Page\PageRepositoryInterface')->orderBy('updated_at', 'desc')->all();
        foreach($pages_list as $page){
            $site_pages[] = [
                'url' => route('front.page', $page->key),
                'last_mod' => $page->updated_at
            ];
        }

        // palmares
        $site_pages[] = [
            'url' => route('front.palmares'),
            'last_mod' => \App::make('App\Repositories\Palmares\PalmaresEventRepositoryInterface')
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at
        ];

        // leading team
        $site_pages[] = [
            'url' => route('front.leading_team'),
            'last_mod' => \App::make('App\Repositories\User\UserRepositoryInterface')
                ->orderBy('updated_at', 'desc')
                ->where('status', '<', config('user.status_key.association-member'))
                ->first()
                ->updated_at
        ];

        // registration
        $site_pages[] = [
            'url' => route('front.registration'),
            'last_mod' => \App::make('App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface')
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at
        ];

        // schedule
        $site_pages[] = [
            'url' => route('front.schedule'),
            'last_mod' => \App::make('App\Repositories\Schedule\ScheduleRepositoryInterface')
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at
        ];

        return $site_pages;
    }
}