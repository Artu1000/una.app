<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PermissionHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Permission';
    }
}