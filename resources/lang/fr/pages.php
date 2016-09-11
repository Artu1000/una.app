<?php

return [
    
    // pages
    "page"    => [
        "title"  => [
            "management" => "Gestion des pages",
            "create"     => "Création d'une page",
            "edit"       => "Édition de la page <b>:page</b>",
            "list"       => "Liste des pages",
            "data"       => "Données de la page",
            "seo"        => "Référencement de la page",
            "release"    => "Diffusion de la page",
        ],
        "action" => [
            "create" => "Créer la page",
            "update" => "Éditer la page",
            "delete" => "Supprimer la page",
        ],
        "info"   => [
            "image"            => "Largeur min : 2560px - Hauteur min : 1440px - Format : jpg, jpeg, png.",
            "meta_title"       => "Si non renseigné, le titre de la page sera utilisé.",
            "meta_description" => "Si non renseignée, le contenu de la page sera utilisé.",
            "content"          => "Longueur optimale du contenu rédactionnel pour le référencement : 500 mots.",
        ],
        "label"  => [
            "image"                  => "Image",
            "slug"                   => "Clé",
            "title"                  => "Titre",
            "meta_title"             => "Meta-title",
            "meta_keywords"          => "Meta-keywords",
            "meta_description"       => "Meta-description",
            "content"                => "Contenu",
            "created_at"             => "Date création",
            "updated_at"             => "Date modification",
            "activation"             => "Activation",
            "activation_placeholder" => "Statut",
        ],
    ],
    
    // messages
    "message" => [
        "create"     => [
            "success" => "La page <b>:page</b> a bien été créée.",
            "failure" => "Une erreur est survenue lors de la création de la page <b>:page</b>.",
        ],
        "update"     => [
            "success" => "La page <b>:page</b> a bien été mise à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de la page <b>:page</b>.",
        ],
        "find"       => [
            "failure" => "La page <b>#:id</b> n'existe pas.",
        ],
        "delete"     => [
            "success" => "La page <b>:page</b> a bien été supprimée.",
            "failure" => "Une erreur est survenue lors de la suppression de la page <b>:page</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "La page <b>:page</b> a bien été :action.",
                "action" => "{0}désactivée|{1}activée",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation de la page <b>:page</b>",
        ],
    ],
];