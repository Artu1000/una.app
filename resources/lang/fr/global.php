<?php

return [
    // actions
    "action"     => [
        "add"      => "Ajouter",
        "edit"     => "Éditer",
        "delete"   => "Supprimer",
        "confirm"  => "Confirmer",
        "cancel"   => "Annuler",
        "back"     => "Retour",
        "browse"   => "Parcourir",
        "activate" => "Activation",
        "close"    => "Fermer",
        "save"     => "Enregistrer les modifications",
    ],

    // messages
    "message"    => [
        "global" => [
            "failure" => [
                "contact" => [
                    "support" => "Veuillez contact l'équipe support si le problème persiste : :email",
                ],
            ],
        ],
    ],

    // modals
    'modal'      => [
        'confirm' => [
            'title'    => 'Demande de confirmation',
            'question' => "Confirmez-vous l'exécution de l'action ci-dessous ?",
        ],
        'alert'   => [
            "title" => [
                "success" => "Succès",
                "info"    => "Infos",
                "error"   => "Erreur",
            ],
        ],
    ],

    // table list
    "table_list" => [
        "placeholder" => [
            "search" => "Rechercher",
            "lines"  => "Nombre de lignes",
        ],
        "column"      => [
            "actions" => "Actions",
        ],
        "results"     => [
            "empty" => "Aucun résultat trouvé.",
            "status" => "Résultats <b>:start</b> à <b>:stop</b> - Total : <b>:total</b>"
        ],
    ],
];