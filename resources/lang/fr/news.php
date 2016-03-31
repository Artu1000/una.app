<?php

return [

    // config
    "config"  => [
        "category" => [
            "club"  => "Club",
            "sport" => "Sport",
        ],
    ],

    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des actualités",
            "create"     => "Création d'une actualité",
            "edit"       => "Édition de l'actualité <b>:news</b>",
            "list"       => "Liste des actualité",
            "data"       => "Données de l'actualité",
            "seo"        => "Référencement de l'actualité",
            "release"    => "Diffusion de l'actualité",
        ],
        "action" => [
            "create" => "Créer l'actualité",
            "update" => "Éditer l'actualité",
            "delete" => "Supprimer l'actualité",
        ],
        "info"   => [
            "image"            => "Largeur min : 2560px - Hauteur min : 1440px - Format : jpg.",
            "meta_title"       => "Si non renseigné, le titre de l'actualité sera utilisé.",
            "meta_description" => "Si non renseignée, le contenu de l'actualité sera utilisé.",
            "content"          => "Longueur optimale de l'article pour le référencement : 500 mots. Longueur minimale : 1000 caractères.",
            "release_date"     => "La news ne sera diffusée qu'à partir de la date et l'heure sélectionnées.",
        ],
        "label"  => [
            "image"                  => "Image",
            "title"                  => "Titre",
            "meta_title"             => "Meta-title",
            "meta_keywords"          => "Meta-keywords",
            "meta_description"       => "Meta-description",
            "content"                => "Contenu",
            "category"               => "Catégorie",
            "released_at"            => "Date de diffusion",
            "activation"             => "Activation",
            "activation_placeholder" => "Statut",
        ],
    ],

    // messages
    "message" => [
        "create"     => [
            "success" => "L'actualité <b>:news</b> a bien été créée.",
            "failure" => "Une erreur est survenue lors de la création de l'actualité <b>:news</b>.",
        ],
        "update"     => [
            "success" => "L'actualité <b>:news</b> a bien été mise à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'actualité <b>:news</b>.",
        ],
        "find"       => [
            "failure" => "L'actualité #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "L'actualité <b>:news</b> a bien été supprimée.",
            "failure" => "Une erreur est survenue lors de la suppression de l'actualité <b>:news</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "L'actualité <b>:news</b> a bien été :action.",
                "action" => "{0}désactivée|{1}activée",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation de l'actualité <b>:news</b>",
        ],
    ],
];