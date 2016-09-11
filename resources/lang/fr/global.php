<?php

return [
    // actions
    "action"     => [
        "add"         => "Ajouter",
        "edit"        => "Éditer",
        "show"        => "Voir le détail",
        "delete"      => "Supprimer",
        "confirm"     => "Confirmer",
        "cancel"      => "Annuler",
        "back"        => "Retour",
        "browse"      => "Parcourir",
        "activate"    => "Activation",
        "close"       => "Fermer",
        "save"        => "Enregistrer les modifications",
        "more"        => "Lire plus",
        "sort_by_cat" => "Trier par catégorie",
    ],
    
    // legend
    "legend"     => [
        "title"     => "Légende",
        "required"  => "Champs obligatoires.",
        "translate" => "Champs à traduire dans différentes langues à l'aide des onglets ci-dessous.",
    ],
    
    // label
    "label"      => [
        "created_at" => "Date de création",
        "updated_at" => "Date de modification",
    ],
    
    // infos
    "info"       => [
        "password" => [
            "length" => "6 catactères minimum. Nous recommandons un mélange de lettres majuscules et minuscules, de nombres et de caractères spéciaux.",
        ],
        "time"     => [
            "format" => "Format : hh:mm (exemple : 10:37).",
        ],
        "date"     => [
            "format" => "Format : jj/mm/aaaa (exemple : 24/03/1985).",
        ],
        "datetime" => [
            "format" => "Format : jj/mm/aaaa hh:mm (exemple : 24/03/1985 15:17).",
        ],
        "year"     => [
            "format" => "Format : aaaa (exemple : 1990).",
        ],
    ],
    
    // messages
    "message"    => [
        "global"     => [
            "failure" => [
                "contact" => [
                    "support" => "Veuillez contacter l'équipe support si le problème persiste : <a href='mailto::email' title='Contact the support team' class='underline'>:email</a>",
                ],
            ],
        ],
        "javascript" => [
            "deactivated" => [
                "title"   => "Attention",
                "message" => "Le Javascript de votre navigateur est désactivé et vous naviguez actuellement en version dégradée.<br/> Merci de réactiver votre Javascript pour bénéficier de l'ensemble des fonctionnalités de l'application.",
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
    
    // file
    "file"       => [
        "download" => [
            "error" => "Le téléchargement du fichier a échoué.",
        ],
    ],
    
    // table list
    "table_list" => [
        "placeholder" => [
            "search" => "Rechercher par",
            "lines"  => "Nombre de lignes",
        ],
        "column"      => [
            "actions" => "Actions",
        ],
        "results"     => [
            "empty"  => "Aucun résultat trouvé.",
            "status" => "Résultats <b>:start</b> à <b>:stop</b> sur un total de <b>:total</b>",
        ],
    ],
];