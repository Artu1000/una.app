<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TableListHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TableList';
    }
}