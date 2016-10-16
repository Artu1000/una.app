<?php

namespace App\Providers;

use App\Helpers\ConsoleHelper;
use App\Helpers\CustomLogHelper;
use App\Helpers\EntryHelper;
use App\Helpers\EnvHelper;
use App\Helpers\FileManagerHelper;
use App\Helpers\ImageManagerHelper;
use App\Helpers\ModalHelper;
use App\Helpers\PermissionHelper;
use App\Helpers\TableListHelper;
use App\Helpers\ValidationHelper;
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
        App::bind('Modal', function () {
            return new ModalHelper();
        });

        // image manager helper
        App::bind('ImageManager', function () {
            return new ImageManagerHelper();
        });
    
        // file manager helper
        App::bind('FileManager', function () {
            return new FileManagerHelper();
        });

        // console helper
        App::bind('Console', function () {
            return new ConsoleHelper();
        });

        // permission helper
        App::bind('Permission', function () {
            return new PermissionHelper();
        });

        // validation helper
        App::bind('Validation', function () {
            return new ValidationHelper();
        });

        // table list helper
        App::bind('TableList', function () {
            return new TableListHelper();
        });

        // custom log helper
        App::bind('CustomLog', function () {
            return new CustomLogHelper();
        });
    
        // env helper
        App::bind('Env', function () {
            return new EnvHelper();
        });
    }
}
