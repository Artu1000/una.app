<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ModalHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Modal';
    }
}