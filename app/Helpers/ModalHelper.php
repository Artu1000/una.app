<?php

namespace App\Helpers;

class ModalHelper
{

    /**
     * @param array $messages
     * @param null $type
     * @param bool|true $before_reload
     */
    public function alert(array $messages, $type = null, $before_reload = true)
    {
        \Session::set("alert", [
            "message" => $this->formatAlertMessage($messages, $type),
            "before_reload" => $before_reload
        ]);
    }

    /**
     * @param array $messages
     */
    public function confirm(array $data)
    {
        if(!isset($data['action']) || empty($data['action'])){
            throw new \Exception('confirm modal data array require an action key.');
        }
        if(!isset($data['attributes']) || empty($data['attributes'])){
            throw new \Exception('confirm modal data array require an attributes key.');
        }
        \Session::set("confirm", $data);
    }

    /**
     * @param array $messages
     * @param null $type
     * @return array
     */
    public function formatAlertMessage(array $messages, $type)
    {
        // we give style to the message
        switch ($type) {
            case 'success':
                $class = 'text-success';
                $title = '<i class="fa fa-thumbs-up"></i> Succès';
                $icon = '<span class="' . $class . '"><i class="fa fa-check"></i></span>';
                break;
            case 'error':
                $class = 'text-danger';
                $title = '<i class="fa fa-thumbs-down"></i> Erreur';
                $icon = '<span class="' . $class . '"><i class="fa fa-times"></i></span>';
                break;
            case 'info':
            default:
                $class = 'text-info';
                $title = '<i class="fa fa-bullhorn"></i> Info';
                $icon = '<span class="' . $class . '"><i class="fa fa-info-circle"></i></span>';
                break;
        }

        // we format the message in html list
        $formattedMessage = '<ul class="list-unstyled">';
        foreach ($messages as $key => $message) {
            $formattedMessage .= '<li>' . $icon . '&nbsp;' . $message . '</li>';
        }
        $formattedMessage .= '</ul>';

        return [
            'class' => $class,
            'title' => $title,
            'content' => $formattedMessage
        ];
    }
}