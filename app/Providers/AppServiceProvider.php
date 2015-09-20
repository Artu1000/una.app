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
        $this->app->register('Laracasts\Utilities\JavaScript\JavascriptServiceProvider');
    }
}
