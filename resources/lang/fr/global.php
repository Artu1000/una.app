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
                    "support" => "Veuillez contacter l'équipe support si le problème persiste : <a href='mailto::email' title='Contact the support team' class='underline'>:email</a>",
                ],
            ],
        ],
        "javascript" => [
            "deactivated" => [
                "title" => "Attention",
                "message" => "Le Javascript de votre navigateur est désactivé et vous naviguez actuellement en version dégradée.<br/> Merci de réactiver votre Javascript pour bénéficier de l'ensemble des fonctionnalités de l'application."
            ]
        ]
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
            "status" => "Résultats <b>:start</b> à <b>:stop</b> sur un total de <b>:total</b>"
        ],
    ],
];