<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EntryHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Entry';
    }
}