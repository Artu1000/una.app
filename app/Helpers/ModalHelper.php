<?php

namespace App\Helpers;

class ModalHelper
{

    public function alert(array $message, $type = null)
    {
        $message = $this->formatMessage($message, $type);
        \Session::set("alert", $message);
    }

    public function formatMessage(array $message, $type = null)
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
        foreach ($message as $key => $message) {
            $formattedMessage .= '<li>' . $icon . ' ' . $message . '</li>';
        }
        $formattedMessage .= '</ul>';

        return [
            'class' => $class,
            'title' => $title,
            'message' => $formattedMessage
        ];
    }
}