<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomLogHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CustomLog';
    }
}