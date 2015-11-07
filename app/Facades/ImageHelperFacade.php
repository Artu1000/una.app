<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ImageHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ImageManager';
    }
}