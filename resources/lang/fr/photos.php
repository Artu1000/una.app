<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des albums photos",
            "content"    => "Contenu de la page",
            "create"     => "Création d'un album photos",
            "edit"       => "Édition de l'album photos <b>:album</b>",
            "list"       => "Liste des albums photos",
            "data"       => "Données de l'album photos",
            "release"    => "Diffusion de l'album photos",
        ],
        "action" => [
            "create" => "Créer l'album photos",
            "update" => "Éditer l'album photos",
            "delete" => "Supprimer l'album photos",
            "year"   => "Choisir une année",
        ],
        "info"   => [
            "cover" => "Largeur min : 150px - Hauteur min : 150px - Format : jpg, jpeg, png.",
            "link"  => "Lien de l'album Google Photo (doit être partagé au public).",
        ],
        "label"  => [
            "cover"                  => "Couverture",
            "title"                  => "Titre",
            "link"                   => "Lien",
            "year"                   => "Année",
            "activation"             => "Activation",
            "activation_placeholder" => "Statut",
            "description"            => "Description",
            "year_placeholder"       => "Sélectionnez une année",
        ],
    ],

    // messages
    "message" => [
        "content"    => [
            "update" => [
                "success" => "La page d'albums photos a bien été mise à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour de la page d'albums photos.",
            ],
        ],
        "create"     => [
            "success" => "L'album photos <b>:album</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création de l'album photos <b>:album</b>.",
        ],
        "update"     => [
            "success" => "L'album photos <b>:album</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'album photos <b>:album</b>.",
        ],
        "find"       => [
            "failure" => "L'album photos #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "L'album photos <b>:album</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression de l'album photos <b>:album</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "L'album photos <b>:album</b> a bien été :action.",
                "action" => "{0}désactivé|{1}activé",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation de l'album photo <b>:album</b>",
        ],
    ],
];