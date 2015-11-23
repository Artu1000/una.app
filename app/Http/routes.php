<?php

/***********************************************************************************************************************
 * LOGS
 **********************************************************************************************************************/

// we enable the query logs
\DB::enableQueryLog();

// log each http request
if (!empty($_SERVER['REQUEST_URI'])) {
    $method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    $uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    \Log::info(implode(' - ', array($method, $uri)));
}
// log each database query
//Event::listen('illuminate.query', function ($sql, $bindings) {
//    foreach ($bindings as $val) {
//        $sql = preg_replace('/\?/', "'{$val}'", $sql, 1);
//    }
//    \Log::info($sql);
//});

/***********************************************************************************************************************
 * IMAGES
 **********************************************************************************************************************/
Route::get('file', [
    'uses' => 'File\FileController@image',
    'as' => 'image'
]);

/***********************************************************************************************************************
 * BACKEND ROUTES
 **********************************************************************************************************************/
$group = config('settings.multilingual') ? ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'auth',
    'localeSessionRedirect',
    'localizationRedirect'
]] : ['middleware' => ['auth']];

// logged routes
$route = Route::group($group, function () {
    // dashboard
    Route::resource(LaravelLocalization::transRoute('routes.dashboard'), 'Dashboard\DashboardController', [
        'names' => [
            'index' => 'dashboard'
        ]
    ]);
    // account
    Route::resource(LaravelLocalization::transRoute('routes.my-account'), 'Auth\AccountController', [
        'names' => [
            'index' => 'account',
            'update' => 'account.update'
        ]
    ]);
    // configuration
    Route::resource(LaravelLocalization::transRoute('routes.settings'), 'Settings\SettingsController', [
        'names' => [
            'index' => 'settings',
            'update' => 'settings.update'
        ], 'except' => [
            'show'
        ]
    ]);

    // permissions
    Route::resource(LaravelLocalization::transRoute('routes.permissions'), 'User\PermissionsController', [
        'names' => [
            'index' => 'permissions.index',
            'create' => 'permissions.create',
            'show' => 'permissions.show',
            'update' => 'permissions.update',
            'destroy' => 'permissions.destroy'
        ]
    ]);

    // users
    Route::resource(LaravelLocalization::transRoute('routes.users'), 'User\UsersController', [
        'names' => [
            'index' => 'users.index',
            'create' => 'users.create',
            'show' => 'users.show',
            'update' => 'users.update',
            'destroy' => 'users.destroy'
        ]
    ]);

    // logout
    Route::resource('deconnexion', 'Auth\LogoutController', [
        'names' => [
            'index' => 'logout'
        ]
    ]);
});

/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/
// guest routes
// we define the middlewares to apply according to the config
$group = config('settings.multilingual') ? ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'guest',
    'localeSessionRedirect',
    'localizationRedirect'
]] : ['middleware' => 'guest'];
Route::group($group, function () {
    // account
    Route::get('mon-compte/creer', [
        'uses' => 'Auth\AccountController@createAccount',
        'as' => 'create_account'
    ]);
    Route::get('mon-compte/renvoi-email-activation', [
        'uses' => 'Auth\AccountController@sendActivationMail',
        'as' => 'send_activation_mail'
    ]);
    Route::get('mon-compte/activation', [
        'uses' => 'Auth\AccountController@activateAccount',
        'as' => 'activate_account'
    ]);
    // connection
    Route::resource('espace-connexion', 'Auth\AuthController', [
        'names' => [
            'index' => 'login',
        ]
    ]);
    // password recovery
    Route::resource('mot-de-passe-oublie', 'Auth\PasswordController', [
        'names' => [
            'index' => 'forgotten_password',
            'show' => 'password_recovery',
            'update' => 'password_reset'
        ]
    ]);
});

//public routes
// we define the middlewares to apply according to the config
$group = config('settings.multilingual') ? ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect'
]] : [];
Route::group($group, function () {
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
});