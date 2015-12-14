<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des partenaires",
            "create"     => "Création d'un partenaire",
            "edit"       => "Édition d'un partenaire",
            "list"       => "Liste des partenaires",
            "data"       => "Données du partenaire",
        ],
        "action" => [
            "create" => "Créer le partenaire",
            "update" => "Éditer le partenaire",
            "delete" => "Supprimer le partenaire",
        ],
        "info"   => [
            "logo"             => "Hauteur min : 100px - Format : png",
            "previous_partner" => "Positionnez le partenaire dans la liste en choisissant celui qui sera placée avant.",
        ],
        "label"  => [
            "logo"                         => "Logo",
            "name"                         => "Nom",
            "url"                          => "url",
            "position"                     => "Position",
            "previous_partner"             => "Partenaire précédent",
            "previous_partner_placeholder" => "Sélectionnez le partenaire précédent",
            "activation"                   => "Activation",
            "activation_placeholder"       => "Statut",
        ],
    ],

    // messages
    "message" => [
        "update" => [
            "success" => "La page d'accueil a bien été mise à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de la page d'accueil.",
        ],
        "slide"  => [
            "create" => [
                "success" => "La diapo a bien été créée.",
                "failure" => "Une erreur est survenue lors de la création de la diapo.",
            ],
            "update" => [
                "success" => "La diapo a bien été mise à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour de la diapo.",
            ],
            "find"   => [
                "failure" => "La diapo #:id n'existe pas.",
            ],
            "delete" => [
                "success" => "La diapo <b>:title</b> a été supprimée avec succès.",
                "failure" => "Une erreur est survenue lors de la suppression de la diapo <b>:title</b>.",
            ],
        ],
    ],
];