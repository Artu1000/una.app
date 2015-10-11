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
        // we format the message in html list
        $formattedMessage = '<ul class="list-unstyled">';
        foreach ($message as $key => $message) {
            $formattedMessage .= '<li>' . $message . '</li>';
        }
        $formattedMessage .= '</ul>';

        // we give style to the message
        switch ($type) {
            case 'success':
                $class = 'text-success';
                $title = '<i class="fa fa-check-circle-o"></i> Félicitations';
                break;
            case 'error':
                $class = 'text-danger';
                $title = '<i class="fa fa-exclamation-triangle"></i> Attention';
                break;
            case 'info':
            default:
                $class = 'text-info';
                $title = '<i class="fa fa-info-circle"></i> Info';
                break;
        }

        return [
            'class' => $class,
            'title' => $title,
            'message' => $formattedMessage
        ];
    }
}