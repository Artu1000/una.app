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
    'glide': './resources/assets/vendor/glidejs/',
    'bootstrap': './resources/assets/vendor/bootstrap-sass-official/assets/',
    'fontawesome': './resources/assets/vendor/fontawesome/',
    'lato': './resources/assets/vendor/lato-font/',
    'js': './resources/assets/js/',
    'img': './resources/assets/img/'
};

elixir(function (mix) {
    mix
        /***************************************************************************************************************
        * SASS
        ***************************************************************************************************************/
        // compile front css
        //.sass("app.front.scss", 'public/css/app.front.css', {})
        // compile back css
        //.sass("app.back.scss", 'public/css/app.back.css', {})
        // compile home css
        .sass("app.home.scss", './resources/assets/compiled_sass_to_merge/app.home.css', {})

        /***************************************************************************************************************
         * CSS
         ***************************************************************************************************************/
         //combine home stylesheets
        .styles([
            'dist/css/glide.core.css',
            'dist/css/glide.theme.css',
            '../../compiled_sass_to_merge/app.home.css'
        ], 'public/css/app.home.css', paths.glide)

        /***************************************************************************************************************
         * IMAGES
         ***************************************************************************************************************/
        // minify images
        .imagemin("**", "public/img/")

        /***************************************************************************************************************
         * FONTS
         ***************************************************************************************************************/
        // copy glyphicon fonts into public folder
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap')
        // copy fontawesome fonts into public folder
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome')
        // copy lato fonts into public folder
        .copy(paths.lato + 'fonts/**', 'public/fonts/lato')

        /***************************************************************************************************************
         * JS
         ***************************************************************************************************************/
        // mix front js files
        .scripts([
            paths.jquery + "dist/jquery.js",
            paths.jquery_easing + "js/jquery.easing.js",
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js'
        ], 'public/js/app.front.js', './')
        // mix back js files
        //.scripts([
        //    paths.jquery + "dist/jquery.js",
        //    paths.bootstrap + "javascripts/bootstrap.js",
        //    paths.js + 'ie10-viewport-bug-workaround.js',
        //    paths.js + 'app.common.js',
        //    paths.js + 'app.back.js'
        //], 'public/js/app.back.js', './')
        // mix home js files
        .scripts([
            paths.glide + 'dist/glide.js',
            paths.js + 'app.home.js'
        ], 'public/js/app.home.js', './')

        /***************************************************************************************************************
         * VERSIONNING
         ***************************************************************************************************************/
        // version all files
        .version([
            //'public/css/app.front.css',
            //'public/css/app.back.css',
            'public/css/app.home.css',

            'public/js/app.front.js',
            //'public/js/app.back.js',
            'public/js/app.home.js'
        ]);
});