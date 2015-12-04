<?php

namespace App\Providers;

use App\Helpers\ConsoleHelper;
use App\Helpers\ImageManagerHelper;
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
        // modal helper
        App::bind('Modal', function(){
            return new ModalHelper();
        });

        // image optimizer helper
        App::bind('ImageManager', function(){
            return new ImageManagerHelper();
        });

        // console helper
        App::bind('Console', function(){
            return new ConsoleHelper();
        });
    }
}
