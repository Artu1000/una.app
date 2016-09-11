<?php

/***********************************************************************************************************************
 * LOGS
 **********************************************************************************************************************/
// log http requests
CustomLog::httpRequests();

// log sql requests
CustomLog::sqlRequests();

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
//$group = [];
if (config('settings.multilingual')) {
    $group = [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['auth', 'localize', 'localeSessionRedirect', 'localizationRedirect',
        ],
    ];
} else {
    $group = ['middleware' => ['auth']];
}

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

    // home page
    Route::get(LaravelLocalization::transRoute('routes.home.page.edit'), ['as' => 'home.page.edit', 'uses' => 'Home\HomeController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.home.page.update'), ['as' => 'home.page.update', 'uses' => 'Home\HomeController@pageUpdate']);

    // slides
    Route::get(LaravelLocalization::transRoute('routes.home.slides.create'), ['as' => 'home.slides.create', 'uses' => 'Home\SlidesController@create']);
    Route::post(LaravelLocalization::transRoute('routes.home.slides.store'), ['as' => 'home.slides.store', 'uses' => 'Home\SlidesController@store']);
    Route::get(LaravelLocalization::transRoute('routes.home.slides.edit'), ['as' => 'home.slides.edit', 'uses' => 'Home\SlidesController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.home.slides.update'), ['as' => 'home.slides.update', 'uses' => 'Home\SlidesController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.home.slides.destroy'), ['as' => 'home.slides.destroy', 'uses' => 'Home\SlidesController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.home.slides.activate'), ['as' => 'home.slides.activate', 'uses' => 'Home\SlidesController@activate']);

    // news page
    Route::get(LaravelLocalization::transRoute('routes.news.page.edit'), ['as' => 'news.page.edit', 'uses' => 'News\NewsController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.news.page.update'), ['as' => 'news.page_update', 'uses' => 'News\NewsController@pageUpdate']);

    // news
    Route::get(LaravelLocalization::transRoute('routes.news.create'), ['as' => 'news.create', 'uses' => 'News\NewsController@create']);
    Route::post(LaravelLocalization::transRoute('routes.news.store'), ['as' => 'news.store', 'uses' => 'News\NewsController@store']);
    Route::get(LaravelLocalization::transRoute('routes.news.edit'), ['as' => 'news.edit', 'uses' => 'News\NewsController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.news.update'), ['as' => 'news.update', 'uses' => 'News\NewsController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.news.destroy'), ['as' => 'news.destroy', 'uses' => 'News\NewsController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.news.activate'), ['as' => 'news.activate', 'uses' => 'News\NewsController@activate']);
    Route::get(LaravelLocalization::transRoute('routes.news.preview'), ['as' => 'news.preview', 'uses' => 'News\NewsController@preview']);

    // schedules page
    Route::get(LaravelLocalization::transRoute('routes.schedules.page.edit'), ['as' => 'schedules.page.edit', 'uses' => 'Schedule\ScheduleController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.schedules.page.update'), ['as' => 'schedules.page.update', 'uses' => 'Schedule\ScheduleController@pageUpdate']);

    // schedules
    Route::get(LaravelLocalization::transRoute('routes.schedules.create'), ['as' => 'schedules.create', 'uses' => 'Schedule\ScheduleController@create']);
    Route::post(LaravelLocalization::transRoute('routes.schedules.store'), ['as' => 'schedules.store', 'uses' => 'Schedule\ScheduleController@store']);
    Route::get(LaravelLocalization::transRoute('routes.schedules.edit'), ['as' => 'schedules.edit', 'uses' => 'Schedule\ScheduleController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.schedules.update'), ['as' => 'schedules.update', 'uses' => 'Schedule\ScheduleController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.schedules.destroy'), ['as' => 'schedules.destroy', 'uses' => 'Schedule\ScheduleController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.schedules.activate'), ['as' => 'schedules.activate', 'uses' => 'Schedule\ScheduleController@activate']);

    // registration page
    Route::get(LaravelLocalization::transRoute('routes.registration.page.edit'), ['as' => 'registration.page.edit', 'uses' => 'Registration\RegistrationController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.registration.page.update'), ['as' => 'registration.page_update', 'uses' => 'Registration\RegistrationController@pageUpdate']);
    
    // registration prices
    Route::get(LaravelLocalization::transRoute('routes.registration.prices.create'), ['as' => 'registration.prices.create', 'uses' => 'Registration\RegistrationPriceController@create']);
    Route::post(LaravelLocalization::transRoute('routes.registration.prices.store'), ['as' => 'registration.prices.store', 'uses' => 'Registration\RegistrationPriceController@store']);
    Route::get(LaravelLocalization::transRoute('routes.registration.prices.edit'), ['as' => 'registration.prices.edit', 'uses' => 'Registration\RegistrationPriceController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.registration.prices.update'), ['as' => 'registration.prices.update', 'uses' => 'Registration\RegistrationPriceController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.registration.prices.destroy'), ['as' => 'registration.prices.destroy', 'uses' => 'Registration\RegistrationPriceController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.registration.prices.activate'), ['as' => 'registration.prices.activate', 'uses' => 'Registration\RegistrationPriceController@activate']);
    
    // pages
    Route::get(LaravelLocalization::transRoute('routes.pages.index'), ['as' => 'pages.index', 'uses' => 'Pages\PageController@index']);
    Route::get(LaravelLocalization::transRoute('routes.pages.create'), ['as' => 'pages.create', 'uses' => 'Pages\PageController@create']);
    Route::post(LaravelLocalization::transRoute('routes.pages.store'), ['as' => 'pages.store', 'uses' => 'Pages\PageController@store']);
    Route::get(LaravelLocalization::transRoute('routes.pages.edit'), ['as' => 'pages.edit', 'uses' => 'Pages\PageController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.pages.update'), ['as' => 'pages.update', 'uses' => 'Pages\PageController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.pages.destroy'), ['as' => 'pages.destroy', 'uses' => 'Pages\PageController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.pages.activate'), ['as' => 'pages.activate', 'uses' => 'Pages\PageController@activate']);
    
    // photos page
    Route::get(LaravelLocalization::transRoute('routes.photos.page.edit'), ['as' => 'photos.page.edit', 'uses' => 'Media\PhotosController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.photos.page.update'), ['as' => 'photos.page_update', 'uses' => 'Media\PhotosController@pageUpdate']);

    // photos
    Route::get(LaravelLocalization::transRoute('routes.photos.create'), ['as' => 'photos.create', 'uses' => 'Media\PhotosController@create']);
    Route::post(LaravelLocalization::transRoute('routes.photos.store'), ['as' => 'photos.store', 'uses' => 'Media\PhotosController@store']);
    Route::get(LaravelLocalization::transRoute('routes.photos.edit'), ['as' => 'photos.edit', 'uses' => 'Media\PhotosController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.photos.update'), ['as' => 'photos.update', 'uses' => 'Media\PhotosController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.photos.destroy'), ['as' => 'photos.destroy', 'uses' => 'Media\PhotosController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.photos.activate'), ['as' => 'photos.activate', 'uses' => 'Media\PhotosController@activate']);

    // videos page
    Route::get(LaravelLocalization::transRoute('routes.videos.page.edit'), ['as' => 'videos.page.edit', 'uses' => 'Media\VideosController@pageEdit']);
    Route::put(LaravelLocalization::transRoute('routes.videos.page.update'), ['as' => 'videos.page_update', 'uses' => 'Media\VideosController@pageUpdate']);

    // videos
    Route::get(LaravelLocalization::transRoute('routes.videos.create'), ['as' => 'videos.create', 'uses' => 'Media\VideosController@create']);
    Route::post(LaravelLocalization::transRoute('routes.videos.store'), ['as' => 'videos.store', 'uses' => 'Media\VideosController@store']);
    Route::get(LaravelLocalization::transRoute('routes.videos.edit'), ['as' => 'videos.edit', 'uses' => 'Media\VideosController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.videos.update'), ['as' => 'videos.update', 'uses' => 'Media\VideosController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.videos.destroy'), ['as' => 'videos.destroy', 'uses' => 'Media\VideosController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.videos.activate'), ['as' => 'videos.activate', 'uses' => 'Media\VideosController@activate']);

    // partners
    Route::get(LaravelLocalization::transRoute('routes.partners.index'), ['as' => 'partners.index', 'uses' => 'Partner\PartnersController@index']);
    Route::get(LaravelLocalization::transRoute('routes.partners.create'), ['as' => 'partners.create', 'uses' => 'Partner\PartnersController@create']);
    Route::post(LaravelLocalization::transRoute('routes.partners.store'), ['as' => 'partners.store', 'uses' => 'Partner\PartnersController@store']);
    Route::get(LaravelLocalization::transRoute('routes.partners.edit'), ['as' => 'partners.edit', 'uses' => 'Partner\PartnersController@edit']);
    Route::put(LaravelLocalization::transRoute('routes.partners.update'), ['as' => 'partners.update', 'uses' => 'Partner\PartnersController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.partners.destroy'), ['as' => 'partners.destroy', 'uses' => 'Partner\PartnersController@destroy']);
    Route::post(LaravelLocalization::transRoute('routes.partners.activate'), ['as' => 'partners.activate', 'uses' => 'Partner\PartnersController@activate']);
    
    // images library
    Route::get(LaravelLocalization::transRoute('routes.libraries.images.index'), ['as' => 'libraries.images.index', 'uses' => 'Libraries\ImagesController@index']);
    Route::post(LaravelLocalization::transRoute('routes.libraries.images.store'), ['as' => 'libraries.images.store', 'uses' => 'Libraries\ImagesController@store']);
    Route::put(LaravelLocalization::transRoute('routes.libraries.images.update'), ['as' => 'libraries.images.update', 'uses' => 'Libraries\ImagesController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.libraries.images.destroy'), ['as' => 'libraries.images.destroy', 'uses' => 'Libraries\ImagesController@destroy']);
    
    // files library
    Route::get(LaravelLocalization::transRoute('routes.libraries.files.index'), ['as' => 'libraries.files.index', 'uses' => 'Libraries\FilesController@index']);
    Route::post(LaravelLocalization::transRoute('routes.libraries.files.store'), ['as' => 'libraries.files.store', 'uses' => 'Libraries\FilesController@store']);
    Route::put(LaravelLocalization::transRoute('routes.libraries.files.update'), ['as' => 'libraries.files.update', 'uses' => 'Libraries\FilesController@update']);
    Route::delete(LaravelLocalization::transRoute('routes.libraries.files.destroy'), ['as' => 'libraries.files.destroy', 'uses' => 'Libraries\FilesController@destroy']);

    // logout
    Route::get(LaravelLocalization::transRoute('routes.logout'), ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

});

/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/
// guest routes
// we define the middlewares to apply according to the config
$group = config('settings.multilingual') ? [
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['guest', 'localize', 'localeSessionRedirect', 'localizationRedirect'],
] : ['middleware' => ['guest']];
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
$group = config('settings.multilingual') ? [
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect'],
] : [];
Route::group($group, function () {

    // home
    Route::get('/', ['as' => 'home', 'uses' => 'Home\HomeController@show']);

    // news
    Route::get(trans('routes.news.index'), ['as' => 'news.index', 'uses' => 'News\NewsController@index']);
    Route::get(trans('routes.news.show'), ['as' => 'news.show', 'uses' => 'News\NewsController@show']);

    // leading team
    Route::get(trans('routes.leading_team.index'), ['as' => 'front.leading_team', 'uses' => 'LeadingTeam\LeadingTeamController@index']);

    // palmares
    Route::get(trans('routes.palmares.index'), ['as' => 'palmares.index', 'uses' => 'Palmares\PalmaresController@index']);

    // registration
    Route::get(trans('routes.registration.index'), ['as' => 'registration.index', 'uses' => 'Registration\RegistrationController@index']);

    // calendar
    Route::get(trans('routes.calendar.index'), ['as' => 'calendar.index', 'uses' => 'Calendar\CalendarController@index']);

    // schedule
    Route::get(trans('routes.schedules.index'), ['as' => 'schedules.index', 'uses' => 'Schedule\ScheduleController@index']);

    // e-shop
    Route::get(trans('routes.e-shop.index'), ['as' => 'e-shop.index', 'uses' => 'EShop\EShopController@index']);
    Route::post(trans('routes.e-shop.index'), ['as' => 'e-shop.add-to-cart', 'uses' => 'EShop\EShopController@addToCart']);

    // photos
    Route::get(trans('routes.photos.index'), ['as' => 'photos.index', 'uses' => 'Media\PhotosController@index']);

    // videos
    Route::get(trans('routes.videos.index'), ['as' => 'videos.index', 'uses' => 'Media\VideosController@index']);

    // pages
    Route::get(trans('routes.page.show'), ['as' => 'page.show', 'uses' => 'Pages\PageController@show']);

    // sitemap
    Route::get(trans('routes.sitemap.index'), ['as' => 'sitemap.index', 'uses' => 'Sitemap\SitemapController@index']);

    // active rss according to the settings setup
    if (config('settings.rss')) Route::get(trans('routes.rss.index'), ['as' => 'rss.index', 'uses' => 'Rss\RssController@index']);

    // proxies
    Route::get(trans('routes.proxy.qr'), ['as' => 'proxy.qr', 'uses' => 'Proxy\ProxyController@qrCodeScanCapture']);
    
    // file download
    Route::get('download/file', ['as' => 'file.download', 'uses' => 'File\FileController@download']);
});