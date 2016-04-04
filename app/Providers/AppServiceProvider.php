<?php

namespace App\Providers;

use Approached\LaravelImageOptimizer\ServiceProvider as LaravelImageOptimizerServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Cartalyst\Sentinel\Laravel\SentinelServiceProvider;
use Cviebrock\ImageValidator\ImageValidatorServiceProvider;
use DaveJamesMiller\Breadcrumbs\ServiceProvider as BreadcrumbsServiceProvider;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Laracasts\Generators\GeneratorsServiceProvider;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Propaganistas\LaravelPhone\LaravelPhoneServiceProvider;

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
            $this->app->register(GeneratorsServiceProvider::class);

            // ide helper
            $this->app->register(IdeHelperServiceProvider::class);
        }

        // laracast javascript service
        // https://github.com/laracasts/PHP-Vars-To-Js-Transformer
        $this->app->register(JavaScriptServiceProvider::class);

        // image optimizer
        // https://github.com/approached/laravel-image-optimizer
        $this->app->register(LaravelImageOptimizerServiceProvider::class);

        // sentinel
        // https://cartalyst.com/manual/sentinel/2.0
        $this->app->register(SentinelServiceProvider::class);

        // intervention image management library
        // http://image.intervention.io/getting_started/installation#laravel
        $this->app->register(ImageServiceProvider::class);

        // laravel phone validator
        // https://github.com/Propaganistas/Laravel-Phone
        $this->app->register(LaravelPhoneServiceProvider::class);

        // laravel multilingual package
        // https://github.com/mcamara/laravel-localization
        $this->app->register(LaravelLocalizationServiceProvider::class);

        // laravel image validator
        // https://github.com/cviebrock/image-validator
        $this->app->register(ImageValidatorServiceProvider::class);

        // laravel breadcrumb
        // http://laravel-breadcrumbs.davejamesmiller.com/en/latest/start.html
        $this->app->register(BreadcrumbsServiceProvider::class);

        // laravel model translatable
        // https://github.com/dimsav/laravel-translatable
        $this->app->register(TranslatableServiceProvider::class);
    }
}
