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
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => [
        'auth',
        'localize',
        'localeSessionRedirect',
        'localizationRedirect',
    ]] : [
    'middleware' => ['auth'],
];

// logged routes
$route = Route::group($group, function () {

    // dashboard
    Route::get(LaravelLocalization::transRoute('routes.dashboard.index'), ['as' => 'dashboard.index', 'uses' => 'Dashboard\DashboardController@index']);
//    Route::get('/', ['as' => 'home', 'uses' => 'Dashboard\DashboardController@index']);

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
    Route::get(LaravelLocalization::transRoute('routes.home.edit'), ['as' => 'home.edit', 'uses' => 'Home\HomeController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.home.update'), ['as' => 'home.update', 'uses' => 'Home\HomeController@update']);

    // slides
    Route::get(LaravelLocalization::transRoute('routes.slides.create'), ['as' => 'slides.create', 'uses' => 'Home\SlidesController@create']);
    Route::post(LaravelLocalization::transRoute('routes.slides.store'), ['as' => 'slides.store', 'uses' => 'Home\SlidesController@store']);
    Route::get(LaravelLocalization::transRoute('routes.slides.edit'), ['as' => 'slides.edit', 'uses' => 'Home\SlidesController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.slides.update'), ['as' => 'slides.update', 'uses' => 'Home\SlidesController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.slides.destroy'), ['as' => 'slides.destroy', 'uses' => 'Home\SlidesController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.slides.activate'), ['as' => 'slides.activate', 'uses' => 'Home\SlidesController@activate']);

    // partners
    Route::get(LaravelLocalization::transRoute('routes.partners.index'), ['as' => 'partners.index', 'uses' => 'Partner\PartnersController@index']);
    Route::get(LaravelLocalization::transRoute('routes.partners.create'), ['as' => 'partners.create', 'uses' => 'Partner\PartnersController@create']);
    Route::post(LaravelLocalization::transRoute('routes.partners.store'), ['as' => 'partners.store', 'uses' => 'Partner\PartnersController@store']);
    Route::get(LaravelLocalization::transRoute('routes.partners.edit'), ['as' => 'partners.edit', 'uses' => 'Partner\PartnersController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.partners.update'), ['as' => 'partners.update', 'uses' => 'Partner\PartnersController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.partners.destroy'), ['as' => 'partners.destroy', 'uses' => 'Partner\PartnersController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.partners.activate'), ['as' => 'partners.activate', 'uses' => 'Partner\PartnersController@activate']);

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
]] : ['middleware' => ['guest']];
Route::group($group, function () {

    // account
    Route::get(LaravelLocalization::transRoute('routes.account.create'), ['as' => 'account.create', 'uses' => 'Auth\AccountController@createAccount']);
    Route::post(LaravelLocalization::transRoute('routes.account.store'), ['as' => 'account.store', 'uses' => 'Auth\AccountController@store']);
    Route::get(LaravelLocalization::transRoute('routes.account.email'), ['as' => 'account.activation_email', 'uses' => 'Auth\AccountController@sendActivationEmail']);
    Route::get(LaravelLocalization::transRoute('routes.account.activation'), ['as' => 'account.activate', 'uses' => 'Auth\AccountController@activateAccount']);

    // login
    Route::get(LaravelLocalization::transRoute('routes.login.index'), ['as' => 'login.index', 'uses' => 'Auth\AuthController@index']);
    Route::post(LaravelLocalization::transRoute('routes.login.login'), ['as' => 'login.login', 'uses' => 'Auth\AuthController@login']);

    // password recovery
    Route::get(LaravelLocalization::transRoute('routes.password.index'), ['as' => 'password.index', 'uses' => 'Auth\PasswordController@index']);
    Route::post(LaravelLocalization::transRoute('routes.password.email'), ['as' => 'password.email', 'uses' => 'Auth\PasswordController@sendResetEmail']);
    Route::get(LaravelLocalization::transRoute('routes.password.reset'), ['as' => 'password.reset', 'uses' => 'Auth\PasswordController@show']);
    Route::put(LaravelLocalization::transRoute('routes.password.update'), ['as' => 'password.update', 'uses' => 'Auth\PasswordController@update']);
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