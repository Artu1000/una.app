<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des partenaires",
            "create"     => "Création d'un partenaire <b>:partner</b>",
            "edit"       => "Édition du partenaire <b>:partner</b>",
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
            "success" => "Le partenaire <b>:partner</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création du partenaire <b>:partner</b>.",
        ],
        "update"     => [
            "success" => "Le partenaire <b>:partner</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour du partenaire <b>:partner</b>.",
        ],
        "find"       => [
            "failure" => "Le partenaire #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "Le partenaire <b>:partner</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression du partenaire <b>:partner</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "Le partenaire <b>:partner</b> a bien été :action.",
                "action" => "{0}désactivé|{1}activé",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du partenaire <b>:partner</b>",
        ],
    ],
];