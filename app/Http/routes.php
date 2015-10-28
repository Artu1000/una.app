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

Route::group([
    'middleware' => 'guest'
], function () {

    Route::resource('creer-un-compte', 'Auth\AccountController', [
        'names' => [
            'index' => 'create_account',
        ]
    ]);

    Route::resource('espace-connexion', 'Auth\AuthController', [
        'names' => [
            'index' => 'login',
        ]
    ]);

    Route::resource('mot-de-passe-oublie', 'Auth\PasswordController', [
        'names' => [
            'index' => 'forgotten_password',
            'show' => 'password_recovery',
            'update' => 'password_reset'
        ]
    ]);
});


Route::group([
    'middleware' => 'auth'
], function () {

    // account
    Route::resource('espace-membre', 'Dashboard\DashboardController', [
        'names' => [
            'index' => 'back.dashboard'
        ]
    ]);

    Route::resource('deconnexion', 'Auth\LogoutController', [
        'names' => [
            'index' => 'logout'
        ]
    ]);
});


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
        'show' => 'front.news.show'
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

// registration
Route::resource('/calendrier', 'Calendar\CalendarController', [
    'names' => [
        'index' => 'front.calendar'
    ]
]);

// schedule
Route::resource('/horaires', 'Schedule\ScheduleController', [
    'names' => [
        'index' => 'front.schedule'
    ]
]);

// shop
Route::resource('/boutique-en-ligne', 'EShop\EShopController', [
    'names' => [
        'index' => 'front.e-shop',
        'show' => 'front.e-shop.add-to-cart'
    ]
]);

// sitemap
Route::get('sitemap.xml', 'Sitemap\SitemapController@index');

// rss
Route::get('rss', 'Rss\RssController@index');

// pages
Route::resource('page', 'Pages\PageController', [
    'names' => [
        'show' => 'front.page'
    ]
]);