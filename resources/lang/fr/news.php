<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des actualités",
            "create"     => "Création d'une actualité",
            "edit"       => "Édition d'une actualité",
            "list"       => "Liste des actualité",
        ],
        "action" => [
            "create" => "Créer l'actualité",
            "update" => "Éditer l'actualité",
            "delete" => "Supprimer l'actualité",
        ],
        "info"   => [
            "image" => "Largeur min : 2560px - Hauteur min : 1440px - Format : jpg",
        ],
        "label"  => [
            "image"            => "Image",
            "title"            => "Titre",
            "meta-title"       => "Meta-title",
            "meta-keywords"    => "Meta-keywords",
            "meta-description" => "Meta-description",
            "content"          => "Contenu",
            "released_at"      => "Date de diffusion",
            "activation"       => "Activation",
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