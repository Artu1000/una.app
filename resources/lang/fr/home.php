<?php

return [

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion de la page d'accueil",
            "content"    => "Contenu",
            "slides"     => "Liste des diapos",
            "slide"      => [
                "create" => "Création d'une diapo",
                "edit"   => "Édition d'une diapo",
                "data"   => "Données de la diapo",
            ],
        ],
        "action" => [
            "slide" => [
                "create" => "Créer la diapo",
                "update" => "Éditer la diapo",
                "delete" => "Supprimer la diapo",
            ],
        ],
        "info"   => [
            "slide" => [
                "picto"            => "Largeur min : 300px / Hauteur min 300px / Format : png.",
                "background_image" => "Largeur min : 2560px / Hauteur min : 1440px / Formats : jpg, jpeg.",
                "previous_slide"   => "Positionnez la diapo dans la liste en choisissant celle qui sera placée avant.",
            ],
        ],
        "label"  => [
            "title"       => "Titre",
            "description" => "Description",
            "video_link"  => "Lien vidéo",
            "slide"       => [
                "background_image"           => "Image de fond",
                "picto"                      => "Picto",
                "title"                      => "Titre",
                "quote"                      => "Citation",
                "position"                   => "Position",
                "previous_slide"             => "Diapo précédente",
                "previous_slide_placeholder" => "--- Choisir diapo précédente ---",
                "first"                      => "Diapo d'accueil (pas de diapo précédente)",
            ],
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