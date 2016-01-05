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
//        if ($this->app->environment() == 'local') {
//            // database generator service
//            $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
//        }

        // laracast javascript service
        // https://github.com/laracasts/PHP-Vars-To-Js-Transformer
        $this->app->register(\Laracasts\Utilities\JavaScript\JavascriptServiceProvider::class);

        // image optimizer
        // https://github.com/approached/laravel-image-optimizer
        $this->app->register(\Approached\LaravelImageOptimizer\ServiceProvider::class);

        // sentinel
        // https://cartalyst.com/manual/sentinel/2.0
        $this->app->register(\Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class);

        // intervention image management library
        // http://image.intervention.io/getting_started/installation#laravel
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);

        // laravel phone validator
        // https://github.com/Propaganistas/Laravel-Phone
        $this->app->register(\Propaganistas\LaravelPhone\LaravelPhoneServiceProvider::class);

        // laravel multilingual package
        // https://github.com/mcamara/laravel-localization
        $this->app->register(\Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class);

        // laravel image validator
        // https://github.com/cviebrock/image-validator
        $this->app->register(\Cviebrock\ImageValidator\ImageValidatorServiceProvider::class);

        // laravel breadcrumb
        // http://laravel-breadcrumbs.davejamesmiller.com/en/latest/start.html
        $this->app->register(\DaveJamesMiller\Breadcrumbs\ServiceProvider::class);

        // laravel model translatable
        // https://github.com/dimsav/laravel-translatable
        $this->app->register(\Dimsav\Translatable\TranslatableServiceProvider::class);
    }
}
