<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EnvHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Env';
    }
}