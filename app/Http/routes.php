<?php

/***********************************************************************************************************************
 * LOGS
 **********************************************************************************************************************/

// log each http request
if (!empty($_SERVER['REQUEST_URI'])) {
    $method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    $uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    \Log::info(implode(' - ', array($method, $uri)));
}

// log each database query
Event::listen('illuminate.query', function ($sql, $bindings) {
    foreach ($bindings as $val) {
        $sql = preg_replace('/\?/', "'{$val}'", $sql, 1);
    }
    Log::info($sql);
});

/***********************************************************************************************************************
 * BACKEND ROUTES
 **********************************************************************************************************************/

//Route::controller('password', 'Auth\PasswordController');

Route::resource('auth', 'Back\Auth\AuthController', [
    'names' => [
        'show' => 'auth.login'
    ]
]);

// Routes with auth needed
Route::group([
    'middleware' => 'auth'
], function () {

// account
    Route::resource('account', 'Back\Account\AccountController', [
        'names' => [
            'index' => 'back.account'
        ]
    ]);

});

/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/

// home
Route::resource('/', 'Front\Home\HomeController', [
    'names' => [
        'index' => 'front.home',
    ]
]);

// news
Route::resource('/news', 'Front\News\NewsController', [
    'names' => [
        'index' => 'front.news.list',
        'show' => 'front.news.detail'
    ]
]);

// palmares
Route::resource('/palmares', 'Front\Palmares\PalmaresController', [
    'names' => [
        'index' => 'front.palmares.list'
    ]
]);

// sitemap
Route::get('sitemap.xml', 'Front\Sitemap\SitemapController@index');

// rss
Route::get('rss', 'Front\Rss\RssController@index');

// at last, pages
Route::resource('/{page_key}', 'Front\Pages\PageController', [
    'names' => [
        'index' => 'front.page'
    ]
]);

