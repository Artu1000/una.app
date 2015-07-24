var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
    'jquery': './resources/assets/vendor/jquery/',
    'bootstrap': './resources/assets/vendor/bootstrap-sass-official/assets/',
    'app' : './resources/assets/js/'
};

elixir(function (mix) {
    mix
        // compile and version front css
        .sass(
            "app.front.scss",
            'public/css/app.front.css',
            {
                includePaths: [
                    paths.bootstrap + 'stylesheets/'
                ]
            }
        )
        // compile and version front css
        .sass(
            "app.back.scss",
            'public/css/app.back.css',
            {
                includePaths: [
                    paths.bootstrap + 'stylesheets/'
                ]
            }
        )
        // copy fonts into public folder
        .copy(
            paths.bootstrap + 'fonts/bootstrap/**',
            'public/build/fonts/bootstrap'
        )
        // mix front js files
        .scripts(
            [
                paths.jquery + "dist/jquery.js",
                paths.bootstrap + "javascripts/bootstrap.js",
                paths.app + 'ie10-viewport-bug-workaround.js',
                paths.app + 'app.common.js',
                paths.app + 'app.front.js'
            ],
            'public/js/app.front.js',
            './'
        )
        // mix back js files
        .scripts(
            [
                paths.jquery + "dist/jquery.js",
                paths.bootstrap + "javascripts/bootstrap.js",
                paths.app + 'ie10-viewport-bug-workaround.js',
                paths.app + 'app.common.js',
                paths.app + 'app.back.js'
            ],
            'public/js/app.back.js',
            './'
        ).version([
            'public/css/app.front.css',
            'public/css/app.back.css',
            'public/js/app.front.js',
            'public/js/app.back.js'
        ]);
});