<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            // database generator service
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }

        // laracast javascript service
        // https://github.com/laracasts/PHP-Vars-To-Js-Transformer
        $this->app->register('Laracasts\Utilities\JavaScript\JavascriptServiceProvider');

        // image optimizer
        // https://github.com/approached/laravel-image-optimizer
        $this->app->register('Approached\LaravelImageOptimizer\ServiceProvider');

        // sentinel
        // https://cartalyst.com/manual/sentinel/2.0
        $this->app->register('Cartalyst\Sentinel\Laravel\SentinelServiceProvider');

        // intervention image management library
        // http://image.intervention.io/getting_started/installation#laravel
        $this->app->register('Intervention\Image\ImageServiceProvider');

        // laravel phone validator
        // https://github.com/Propaganistas/Laravel-Phone
        $this->app->register('Propaganistas\LaravelPhone\LaravelPhoneServiceProvider');

        // laravel multilingual package
        // https://github.com/mcamara/laravel-localization
        $this->app->register('Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider');

        // laravel image validator
        // https://github.com/cviebrock/image-validator
        $this->app->register('Cviebrock\ImageValidator\ImageValidatorServiceProvider');

        // laravel breadcrumb
        // http://laravel-breadcrumbs.davejamesmiller.com/en/latest/start.html
        $this->app->register('DaveJamesMiller\Breadcrumbs\ServiceProvider');
    }
}
