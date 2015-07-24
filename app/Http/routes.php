<?php

/***********************************************************************************************************************
 * LOGS
 **********************************************************************************************************************/

// Log each HTTP request
if (!empty($_SERVER['REQUEST_URI'])) {
    $method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD']:'';
    $uri    = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']: '';
    \Log::info(implode(' - ', array($method, $uri)));
}

// log each database query
Event::listen('illuminate.query', function($sql, $bindings)
{
    foreach ($bindings as $val) {
        $sql = preg_replace('/\?/', "'{$val}'", $sql, 1);
    }

    Log::info($sql);
});


/***********************************************************************************************************************
 * FRONTEND ROUTES
 **********************************************************************************************************************/

//Route::get('/', function () {
//    dd('coucou');
//    return view('welcome');
//});

//Route::controller('home', 'Front\Home\HomeController');

Route::resource('/', 'Front\Home\HomeController', [
    'names' => [
        'home' => 'home.index'
    ]
]);

// Verify csrf for Ajax request
//Route::filter('csrf', function(){
//    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
//    if (Session::token() != $token)
//        throw new Illuminate\Session\TokenMismatchException;
//});




// Routes with no auth needed
// Route::controller('verifier', 'Verifier\ValidityController');


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
], function(){
    Route::resource('account', 'AccountController', [
        'names' => [
            'index' => 'account.index'
        ]
    ]);
});

