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
    jquery: './resources/assets/vendor/jquery/',
    jquery_easing: './resources/assets/vendor/jquery.easing/',
    glide: './resources/assets/vendor/glidejs/',
    bootstrap: './resources/assets/vendor/bootstrap-sass-official/assets/',
    datepicker: './resources/assets/vendor/eonasdan-bootstrap-datetimepicker/',
    moment: './resources/assets/vendor/moment/',
    lity: './resources/assets/vendor/lity/dist/',
    notify: './resources/assets/vendor/remarkable-bootstrap-notify/dist/',
    animate: './resources/assets/vendor/animate.css/',
    simplemde: './resources/assets/vendor/simplemde/dist/',
    fontawesome: './resources/assets/vendor/fontawesome/',
    lato: './resources/assets/vendor/lato-font/',
    js: './resources/assets/js/',
    img: './resources/assets/img/'
};

elixir(function (mix) {
    /***************************************************************************************************************
     * SASS
     ***************************************************************************************************************/
    mix

        // FRONT
        .sass('app.front.scss', 'public/css/app.front.css', {})
        .sass('app.home.scss', 'public/css/app.home.css', {})
        .sass('app.page.scss', 'public/css/app.page.css', {})
        .sass('app.palmares.scss', 'public/css/app.palmares.css', {})
        .sass('app.news.scss', 'public/css/app.news.css', {})
        .sass('app.leading-team.scss', 'public/css/app.leading-team.css', {})
        .sass('app.registration.scss', 'public/css/app.registration.css', {})
        .sass('app.schedule.scss', 'public/css/app.schedule.css', {})
        .sass('app.calendar.scss', 'public/css/app.calendar.css', {})
        .sass('app.e-shop.scss', 'public/css/app.e-shop.css', {})
        .sass('app.login.scss', 'public/css/app.login.css', {})

        // COMMON
        .sass('app.error.scss', 'public/css/app.error.css', {})

        // BACK
        .sass('app.back.scss', 'public/css/app.back.css', {})

        /***************************************************************************************************************
         * CSS
         ***************************************************************************************************************/


        //combine home stylesheets
        .styles([
            paths.glide + 'dist/css/glide.core.css',
            paths.glide + 'dist/css/glide.theme.css',
            paths.lity + 'lity.css',
            'public/css/app.home.css'
        ], 'public/css/app.home.css', './')

        // combine back stylesheets
        .styles([
            //paths.datepicker + 'css/bootstrap-datepicker3.css',
            paths.lity + 'lity.css',
            paths.animate + 'animate.css',
            paths.simplemde + 'simplemde.min.css',
            'public/css/app.back.css'
        ], 'public/css/app.back.css', './')

        /***************************************************************************************************************
         * IMAGES
         ***************************************************************************************************************/
        // minify images
        .imagemin('**', 'public/img/')

        /***************************************************************************************************************
         * FONTS
         ***************************************************************************************************************/
        // // copy glyphicon fonts into public folder
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap')
        // // copy fontawesome fonts into public folder
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome')
        // // copy lato fonts into public folder
        .copy(paths.lato + 'fonts/**', 'public/fonts/lato')

        /***************************************************************************************************************
         * JS
         ***************************************************************************************************************/
        // FRONT
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js'
        ], 'public/js/app.front.js', './')
         // mix home js files
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.glide + 'dist/glide.js',
            paths.lity + 'lity.js',
            paths.js + 'app.home.js'
        ], 'public/js/app.home.js', './')
        // // mix news detail js files
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.js + 'app.news-detail.js'
        ], 'public/js/app.news-detail.js', './')

        // BACK
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.moment + 'moment.js',
            paths.moment + 'locale/fr.js',
            paths.moment + 'locale/en-gb.js',
            paths.datepicker + 'src/js/bootstrap-datetimepicker.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.lity + 'lity.js',
            paths.notify + 'bootstrap-notify.js',
            paths.simplemde + 'simplemde.min.js',
            paths.js + 'app.common.js',
            paths.js + 'app.back.js'
        ], 'public/js/app.back.js', './')

        /***************************************************************************************************************
         * VERSIONS
         ***************************************************************************************************************/
        // version all files
        .version([
            // css front
            'public/css/app.front.css',
            'public/css/app.home.css',
            'public/css/app.page.css',
            'public/css/app.palmares.css',
            'public/css/app.news.css',
            'public/css/app.leading-team.css',
            'public/css/app.registration.css',
            'public/css/app.schedule.css',
            'public/css/app.calendar.css',
            'public/css/app.e-shop.css',
            'public/css/app.login.css',
            'public/css/app.error.css',
            // css back
            'public/css/app.back.css',
            // js front
            'public/js/app.front.js',
            'public/js/app.home.js',
            'public/js/app.news-detail.js',
            // js front
            'public/js/app.back.js'
        ]);
});