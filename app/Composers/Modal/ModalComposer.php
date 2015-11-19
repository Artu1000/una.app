<?php

namespace App\Composers\Modal;

class ModalComposer {

    public function __construct()
    {
        //
    }

    public function compose($view)
    {
        // we manage the alert message
        if($alert = \Session::get('alert')){
            $data['alert'] = $alert;
            $view->with('alert', $alert);
            \Session::forget('alert');
        }

        // we manage the confirm message
        if($confirm = \Session::get('confirm')){
            $data['confirm'] = $confirm;
            $view->with('confirm', $confirm);
            \Session::forget('confirm');
        }
    }

}