<?php

namespace App\Providers;

use App\Helpers\ModalHelper;
use App\Helpers\StringHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // string helper
        App::bind('String', function(){
            return new StringHelper();
        });

        // modal
        App::bind('Modal', function(){
            return new ModalHelper();
        });
    }
}
