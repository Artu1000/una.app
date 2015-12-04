<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ConsoleHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Console';
    }
}