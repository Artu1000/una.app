<?php

return [
    
    // views
    "page"    => [
        "title"  => [
            "management" => "Gestion des vidéos",
            "content"    => "Contenu de la page",
            "create"     => "Création d'une vidéo",
            "edit"       => "Édition de la vidéo <b>:video</b>",
            "list"       => "Liste des vidéos",
            "data"       => "Données de la vidéo",
            "release"    => "Diffusion de la vidéo",
        ],
        "action" => [
            "create" => "Créer la vidéo",
            "update" => "Éditer la vidéo",
            "delete" => "Supprimer la vidéo",
            "year"   => "Choisir une année",
        ],
        "info"   => [
            "cover" => "Largeur min : 220px - Hauteur min : 220px - Format : jpg, jpeg, png.",
            "title" => "Prendre soin de définir un titre évocateur du contenu de la vidéo.",
            "link"  => "Lien de la vidéo Youtube (doit être partagée au public). Attention à bien charger la vidéo sur le compte Youtube UNA (contacter <u><a href='mailto:communication@una-club.fr'>communication@una-club.fr</a></u>).",
        ],
        "label"  => [
            "cover"                  => "Couverture",
            "title"                  => "Titre",
            "link"                   => "Lien",
            "date"                   => "Date",
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
                "success" => "La page de vidéos a bien été mise à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour de la page de vidéos.",
            ],
        ],
        "create"     => [
            "success" => "La vidéo <b>:video</b> a bien été créée.",
            "failure" => "Une erreur est survenue lors de la création de la vidéo <b>:video</b>.",
        ],
        "update"     => [
            "success" => "La video <b>:video</b> a bien été mise à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de la vidéo <b>:video</b>.",
        ],
        "find"       => [
            "failure" => "La vidéo <b>#:id</b> n'existe pas.",
        ],
        "delete"     => [
            "success" => "La vidéo <b>:video</b> a bien été supprimée.",
            "failure" => "Une erreur est survenue lors de la suppression de la vidéo <b>:video</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "La vidéo <b>:video</b> a bien été :action.",
                "action" => "{0}désactivée|{1}activée",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation de la vidéo <b>:video</b>",
        ],
    ],
];