// imports
const elixir = require('laravel-elixir');
const gulp = require('gulp');
const shell = require('gulp-shell');
const imagemin = require('gulp-image');
require('laravel-elixir-remove');

// shell elixir extensions
elixir.extend('shell', function () {
    new elixir.Task('command', function () {
        return gulp.src('')
            .pipe(shell('php artisan storage:prepare'))
            .pipe(shell('php artisan symlinks:prepare'));
    });
});

// imagemin elixir extensions
elixir.extend('imagemin', function (src, dest) {
    new elixir.Task('imagemin', function () {
        return gulp.src(src)
            .pipe(imagemin())
            .pipe(gulp.dest(dest));
    });
});

// production variable
const production = elixir.config.production;

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
    assets: './resources/assets/',
    jquery_easing: './resources/assets/vendor/jquery.easing/',
    glide: './resources/assets/vendor/glidejs/',
    bootstrap: './resources/assets/vendor/bootstrap-sass/assets/',
    datepicker: './resources/assets/vendor/eonasdan-bootstrap-datetimepicker/',
    moment: './resources/assets/vendor/moment/',
    lity: './resources/assets/vendor/lity/dist/',
    notify: './resources/assets/vendor/remarkable-bootstrap-notify/dist/',
    animate: './resources/assets/vendor/animate.css/',
    simplemde: './resources/assets/vendor/simplemde/dist/',
    fontawesome: './resources/assets/vendor/fontawesome/',
    lato: './resources/assets/vendor/lato-webfont/',
    dropzone: './resources/assets/vendor/dropzone/dist/',
    js: './resources/assets/js/',
    img: './resources/assets/img/'
};

elixir(function (mix) {

    /***************************************************************************************************************
     * FILE REMOVAL
     ***************************************************************************************************************/
    // if for production
    if (production) {
        // we remove all generated public folders
        mix.remove([
            'public/build',
            'public/css',
            'public/files',
            'public/fonts',
            'public/img',
            'public/js',
            'public/libraries'
        ]);
    }

        /***************************************************************************************************************
         * SASS
         ***************************************************************************************************************/

        // FRONT
        mix.sass('app.front.scss', 'public/css/app.front.css');
        mix.sass('app.home.scss', 'public/css/app.home.css');
        mix.sass('app.page.scss', 'public/css/app.page.css');
        // mix.sass('app.palmares.scss', 'public/css/app.palmares.css');
        mix.sass('app.news.scss', 'public/css/app.news.css');
        mix.sass('app.leading-team.scss', 'public/css/app.leading-team.css');
        mix.sass('app.registration.scss', 'public/css/app.registration.css');
        mix.sass('app.schedule.scss', 'public/css/app.schedule.css');
        mix.sass('app.calendar.scss', 'public/css/app.calendar.css');
        mix.sass('app.e-shop.scss', 'public/css/app.e-shop.css');
        mix.sass('app.photos.scss', 'public/css/app.photos.css');
        mix.sass('app.videos.scss', 'public/css/app.videos.css');
        mix.sass('app.auth.scss', 'public/css/app.auth.css');

        // COMMON
        mix.sass('app.error.scss', 'public/css/app.error.css');

        // BACK
        mix.sass('app.back.scss', 'public/css/app.back.css');

        /***************************************************************************************************************
         * IMAGES
         ***************************************************************************************************************/
        const img_src = './resources/assets/img/';
        const img_dest = './public/img/';
        // if for production
        if (production) {
            // we minify images
            mix.imagemin(img_src + '**', img_dest);
        } else {
            // we copy the images
            mix.copy(img_src, img_dest);
        }

        /***************************************************************************************************************
         * FILES
         ***************************************************************************************************************/
        mix.copy('resources/assets/files/**', 'public/files');

        /***************************************************************************************************************
         * FONTS
         ***************************************************************************************************************/
        // // copy glyphicon fonts into public folder
        mix.copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap');
        // // copy fontawesome fonts into public folder
        mix.copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome');
        // // copy lato fonts into public folder
        mix.copy(paths.lato + 'fonts/**', 'public/fonts/lato');
        // // copy custom icons fonts into public folder
        mix.copy(paths.assets + 'fonts/icons/**', 'public/fonts/icons');

        /***************************************************************************************************************
         * JS
         ***************************************************************************************************************/
        // FRONT
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js'
        ], 'public/js/app.front.js', './');
        // mix home js files
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.glide + 'dist/glide.js',
            paths.lity + 'lity.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.js + 'app.home.js'
        ], 'public/js/app.home.js', './');
        // mix news detail js files
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.lity + 'lity.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.js + 'app.news-detail.js'
        ], 'public/js/app.news-detail.js', './');
        // mix leading team js files
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.lity + 'lity.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js'
        ], 'public/js/app.leading-team.js', './');
        // mix photos js files
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.js + 'app.photos.js'
        ], 'public/js/app.photos.js', './');
        // mix videos js files
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.lity + 'lity.js',
            paths.js + 'app.common.js',
            paths.js + 'app.front.js',
            paths.js + 'app.videos.js'
        ], 'public/js/app.videos.js', './');

        // BACK
        mix.scripts([
            paths.jquery + 'dist/jquery.js',
            paths.jquery_easing + 'js/jquery.easing.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.moment + 'moment.js',
            paths.moment + 'locale/fr.js',
            paths.moment + 'locale/en-gb.js',
            paths.dropzone + 'dropzone.js',
            paths.datepicker + 'src/js/bootstrap-datetimepicker.js',
            paths.js + 'ie10-viewport-bug-workaround.js',
            paths.lity + 'lity.js',
            paths.notify + 'bootstrap-notify.js',
            paths.simplemde + 'simplemde.min.js',
            paths.js + 'app.common.js',
            paths.js + 'app.back.js'
        ], 'public/js/app.back.js', './');

        /***************************************************************************************************************
         * VERSIONS
         ***************************************************************************************************************/
        // version all files
        mix.version([
            // css front
            'public/css/app.front.css',
            'public/css/app.icons.css',
            'public/css/app.auth.css',
            'public/css/app.error.css',
            'public/css/app.home.css',
            'public/css/app.page.css',
            // 'public/css/app.palmares.css',
            'public/css/app.news.css',
            'public/css/app.leading-team.css',
            'public/css/app.registration.css',
            'public/css/app.schedule.css',
            'public/css/app.calendar.css',
            'public/css/app.e-shop.css',
            'public/css/app.photos.css',
            'public/css/app.videos.css',
            // css back
            'public/css/app.back.css',
            // js front
            'public/js/app.front.js',
            'public/js/app.home.js',
            'public/js/app.news-detail.js',
            'public/js/app.leading-team.js',
            'public/js/app.photos.js',
            'public/js/app.videos.js',
            // js front
            'public/js/app.back.js'
        ]);

    /***************************************************************************************************************
     * SHELL COMMANDS
     ***************************************************************************************************************/
    // if for production
    if (production) {
        // we execute some shell commands
        mix.shell();
    }
});
