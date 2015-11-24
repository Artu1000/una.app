<?php

return [
    '404' => "La page n'existe pas",
    '405' => "Action non autorisée",
    '503' => "Site indisponible pour le moment",
    'contact' => "Veuillez contacter le support si l'erreur persiste :" . "<a href='mailto:" .
        config('settings.support_email') . "' >" . config('settings.support_email') . "</a>.",
];