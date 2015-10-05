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

//Route::resource('auth', 'Auth\AuthController', [
//    'names' => [
//        'show' => 'auth.login'
//    ]
//]);

// account
Route::resource('account', 'Account\AccountController', [
    'names' => [
        'index' => 'back.account'
    ]
]);


/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/

// home
Route::resource('/', 'Home\HomeController', [
    'names' => [
        'index' => 'home',
    ]
]);

// news
Route::resource('/news', 'News\NewsController', [
    'names' => [
        'index' => 'front.news',
        'show' => 'front.news.detail'
    ]
]);

// leading team
Route::resource('/equipe-dirigeante', 'LeadingTeam\LeadingTeamController', [
    'names' => [
        'index' => 'front.leading_team'
    ]
]);

// palmares
Route::resource('/palmares', 'Palmares\PalmaresController', [
    'names' => [
        'index' => 'front.palmares'
    ]
]);

// registration
Route::resource('/inscription', 'Registration\RegistrationController', [
    'names' => [
        'index' => 'front.registration'
    ]
]);

// schedule
Route::resource('/horaires', 'Schedule\ScheduleController', [
    'names' => [
        'index' => 'front.schedule'
    ]
]);

// sitemap
Route::get('sitemap.xml', 'Sitemap\SitemapController@index');

// rss
Route::get('rss', 'Rss\RssController@index');

//$except = [
//    '/login',
//    '/forgot',
//    '/register',
//    '/users',
//    '/groups'
//];

// at last, pages
//if(!in_array($uri, $except)) {

    Route::resource('/{page_key}', 'Pages\PageController', [
        'names' => [
            'index' => 'front.page'
        ]
    ]);

//}

