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
    \Log::info(implode(' - ', [$method, $uri]));
}

/***********************************************************************************************************************
 * IMAGES
 **********************************************************************************************************************/
Route::get('file', [
    'uses' => 'File\FileController@image',
    'as'   => 'image',
]);

/***********************************************************************************************************************
 * BACKEND ROUTES
 **********************************************************************************************************************/

$group = config('settings.multilingual') ? [
    'prefix'     => LaravelLocalization::setLocale() . '/' . LaravelLocalization::transRoute('routes.admin'),
    'middleware' => [
        'auth',
        'localize',
        'localeSessionRedirect',
        'localizationRedirect',
    ]] : [
    'prefix'     => 'admin',
    'middleware' => ['auth'],
];

// logged routes
$route = Route::group($group, function () {

    // dashboard
    Route::get(LaravelLocalization::transRoute('routes.dashboard.index'), ['as' => 'dashboard.index', 'uses' => 'Dashboard\DashboardController@index']);

    // settings
    Route::get(LaravelLocalization::transRoute('routes.settings.index'), ['as' => 'settings.index', 'uses' => 'Settings\SettingsController@index']);
    Route::put(LaravelLocalization::transRoute('routes.settings.update'), ['as' => 'settings.update', 'uses' => 'Settings\SettingsController@update']);

    // permissions
    Route::get(LaravelLocalization::transRoute('routes.permissions.index'), ['as' => 'permissions.index', 'uses' => 'User\PermissionsController@index']);
    Route::get(LaravelLocalization::transRoute('routes.permissions.create'), ['as' => 'permissions.create', 'uses' => 'User\PermissionsController@create']);
    Route::post(LaravelLocalization::transRoute('routes.permissions.store'), ['as' => 'permissions.store', 'uses' => 'User\PermissionsController@store']);
    Route::get(LaravelLocalization::transRoute('routes.permissions.edit'), ['as' => 'permissions.edit', 'uses' => 'User\PermissionsController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.permissions.update'), ['as' => 'permissions.update', 'uses' => 'User\PermissionsController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.permissions.destroy'), ['as' => 'permissions.destroy', 'uses' => 'User\PermissionsController@destroy']);

    // users
    Route::get(LaravelLocalization::transRoute('routes.users.index'), ['as' => 'users.index', 'uses' => 'User\UsersController@index']);
    Route::get(LaravelLocalization::transRoute('routes.users.create'), ['as' => 'users.create', 'uses' => 'User\UsersController@create']);
    Route::post(LaravelLocalization::transRoute('routes.users.store'), ['as' => 'users.store', 'uses' => 'User\UsersController@store']);
    Route::get(LaravelLocalization::transRoute('routes.users.edit'), ['as' => 'users.edit', 'uses' => 'User\UsersController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.users.update'), ['as' => 'users.update', 'uses' => 'User\UsersController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.users.destroy'), ['as' => 'users.destroy', 'uses' => 'User\UsersController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.users.activate'), ['as' => 'users.activate', 'uses' => 'User\UsersController@activate']);
    Route::get(LaravelLocalization::transRoute('routes.users.profile'), ['as' => 'users.profile', 'uses' => 'User\UsersController@profile']);

    // home
    $contents = LaravelLocalization::transRoute('routes.contents') . '/';
    Route::get($contents . LaravelLocalization::transRoute('routes.home.edit'), ['as' => 'home.edit', 'uses' => 'Home\HomeController@edit']);

    Route::get(LaravelLocalization::transRoute('routes.slides.create'), ['as' => 'slides.create', 'uses' => 'User\UsersController@create']);
    Route::post(LaravelLocalization::transRoute('routes.slides.store'), ['as' => 'slides.store', 'uses' => 'User\UsersController@store']);
    Route::get(LaravelLocalization::transRoute('routes.slides.edit'), ['as' => 'slides.edit', 'uses' => 'User\UsersController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.slides.update'), ['as' => 'slides.update', 'uses' => 'User\UsersController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.slides.destroy'), ['as' => 'slides.destroy', 'uses' => 'User\UsersController@destroy']);


    // logout
    Route::get(LaravelLocalization::transRoute('routes.logout'), ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

});

/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/
// guest routes
// we define the middlewares to apply according to the config
$group = config('settings.multilingual') ? ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'guest',
    'localize',
    'localeSessionRedirect',
    'localizationRedirect',
]] : ['middleware' => 'guest'];
Route::group($group, function () {
    // account
    Route::get('mon-compte/creer', [
        'uses' => 'Auth\AccountController@createAccount',
        'as'   => 'create_account',
    ]);
    Route::get('mon-compte/renvoi-email-activation', [
        'uses' => 'Auth\AccountController@sendActivationMail',
        'as'   => 'send_activation_mail',
    ]);
    Route::get('mon-compte/activation', [
        'uses' => 'Auth\AccountController@activateAccount',
        'as'   => 'activate_account',
    ]);
    // connection
    Route::resource('espace-connexion', 'Auth\AuthController', [
        'names' => [
            'index' => 'login',
        ],
    ]);
    // password recovery
    Route::resource('mot-de-passe-oublie', 'Auth\PasswordController', [
        'names' => [
            'index'  => 'forgotten_password',
            'show'   => 'password_recovery',
            'update' => 'password_reset',
        ],
    ]);
});

//public routes
// we define the middlewares to apply according to the config
$group = config('settings.multilingual') ? ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localize',
    'localeSessionRedirect',
    'localizationRedirect',
]] : [];
Route::group($group, function () {

    // home
    Route::get('/', ['as' => 'home', 'uses' => 'Home\HomeController@show']);

    // news
    Route::resource('/news', 'News\NewsController', ['names' => ['index' => 'front.news', 'show' => 'front.news.show']]);

    // leading team
    Route::resource('/equipe-dirigeante', 'LeadingTeam\LeadingTeamController', ['names' => ['index' => 'front.leading_team']]);

    // palmares
    Route::resource('/palmares', 'Palmares\PalmaresController', ['names' => ['index' => 'front.palmares']]);

    // registration
    Route::resource('/inscription', 'Registration\RegistrationController', ['names' => ['index' => 'front.registration']]);

    // registration
    Route::resource('/calendrier', 'Calendar\CalendarController', ['names' => ['index' => 'front.calendar']]);

    // schedule
    Route::resource('/horaires', 'Schedule\ScheduleController', ['names' => ['index' => 'front.schedule']]);

    // shop
    Route::resource('/boutique-en-ligne', 'EShop\EShopController', ['names' => ['index' => 'front.e-shop', 'show' => 'front.e-shop.add-to-cart']]);

    // sitemap
    Route::get('sitemap.xml', 'Sitemap\SitemapController@index');

    // pages
    Route::resource('page', 'Pages\PageController', ['names' => ['show' => 'front.page']]);

    // active rss according to the settings setup
    if (config('settings.rss')) Route::get('rss', 'Rss\RssController@index');
});