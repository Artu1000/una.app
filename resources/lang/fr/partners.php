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
            "url"                          => "Url",
            "position"                     => "Position",
            "previous_partner"             => "Partenaire précédent",
            "previous_partner_placeholder" => "Sélectionnez le partenaire précédent",
            "activation"                   => "Activation",
            "activation_placeholder"       => "Statut",
        ],
    ],

    // messages
    "message" => [
        "create"     => [
            "success" => "Le partenaire a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création du partenaire.",
        ],
        "update"     => [
            "success" => "Le partenaire a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour du partenaire.",
        ],
        "find"       => [
            "failure" => "Le partenaire #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "Le partenaire <b>:name</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression du partenaire <b>:name</b>.",
        ],
        "activation" => [
            "success" => "Le statut d'activation du partenaire <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du partenaire <b>:name</b>",
        ],
    ],
];