<?php

namespace App\Composers\Modal;

class ModalComposer {

    public function __construct()
    {
        //
    }

    public function compose($view)
    {
        if($alert = \Session::get('alert')){
            $view->with('alert', $alert);
            \Session::forget('alert');
        }
    }

}