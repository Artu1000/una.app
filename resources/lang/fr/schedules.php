<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des créneaux horaires",
            "list"       => "Liste des créneaux horaires",
            "edit"       => "Édition d'un créneau horaire",
            "create"     => "Création d'un créneau horaire",
            "data"       => "Données",
            "release"    => "Diffusion",
        ],
        "action" => [
            "update" => "Éditer le créneau utilisateur",
            "create" => "Créer le créneau horaire",
            "delete" => "Supprimer le créneau horaire",
        ],
        'info'   => [
        ],
        "label"  => [
            "day_id"                      => "Jour de la semaine",
            "day_placeholder"             => "--- Séléctionnez le jour de la semaine ---",
            "time_start"                  => "Heure de début",
            "time_stop"                   => "Heure de fin",
            "public_category"             => "Catégorie de public",
            "public_category_placeholder" => "--- Séléctionnez une catégorie de public ---",
            "activation"                  => "Activation",
            "activation_placeholder"      => "Statut",
        ],
    ],

    // messages
    "message" => [
        "creation"   => [
            "success" => "Le créneau horaire <b>:label</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création du créneau horaire <b>:label</b>.",
        ],
        "update"     => [
            "success" => "Le créneau horaire <b>:label</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour du créneau horaire <b>:label</b>.",
        ],
        "find"       => [
            "failure" => "Le créneau horaire #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "Le créneau horaire <b>:label</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression du créneau horaire <b>:label</b>.",
        ],
        "activation" => [
            "success" => "Le statut d'activation du créneau horaire <b>:label</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du créneau horaire <b>:label</b>",
        ],
    ],
];