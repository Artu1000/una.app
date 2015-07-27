var elixir = require('laravel-elixir');
require('laravel-elixir-imagemin');

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
    'jquery_easing': './resources/assets/vendor/jquery.easing/',
    'bootstrap': './resources/assets/vendor/bootstrap-sass-official/assets/',
    'fontawesome': './resources/assets/vendor/fontawesome/',
    'lato': './resources/assets/vendor/lato-font/',
    'js' : './resources/assets/js/',
    'img' : './resources/assets/img/'
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
        // copy glyphicon fonts into public folder
        .imagemin("*", "public/img/")
        // copy glyphicon fonts into public folder
        .copy(
            paths.bootstrap + 'fonts/bootstrap/**',
            'public/fonts/bootstrap'
        )
        // copy glyphicon fonts into public folder
        .copy(
            paths.fontawesome + 'fonts/**',
            'public/fonts/fontawesome'
        )
        // copy lato fonts into public folder
        .copy(
            paths.lato + 'fonts/**',
            'public/fonts/lato'
        )
        // mix front js files
        .scripts(
            [
                paths.jquery + "dist/jquery.js",
                paths.jquery_easing + "js/jquery.easing.js",
                paths.bootstrap + "javascripts/bootstrap.js",
                paths.js + 'ie10-viewport-bug-workaround.js',
                paths.js + 'app.common.js',
                paths.js + 'app.front.js'
            ],
            'public/js/app.front.js',
            './'
        )
        // mix back js files
        .scripts(
            [
                paths.jquery + "dist/jquery.js",
                paths.bootstrap + "javascripts/bootstrap.js",
                paths.js + 'ie10-viewport-bug-workaround.js',
                paths.js + 'app.common.js',
                paths.js + 'app.back.js'
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