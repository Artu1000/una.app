<?php

return [
    
    // views
    "page"    => [
        "title"  => [
            "management"          => "Gestion de la page d'inscription",
            "content"             => "Contenu",
            "online_registration" => "Inscription en ligne",
            "our_prices"          => "Nos tarifs",
//            "prices"     => "Liste des tarifs",
            "price"               => [
//                "create" => "Création d'une diapo",
//                "edit"   => "Édition de la diapo <b>:slide</b>",
//                "data"   => "Données de la diapo",
            ],
        ],
        "action" => [
            "price" => [
                "create" => "Créer le tarif",
                "update" => "Éditer le tarif",
                "delete" => "Supprimer le tarif",
            ],
        ],
        "info"   => [
            "background_image" => "Largeur min : 2560px / Hauteur min : 1440px / Formats : jpg, jpeg.",
        ],
        "label"  => [
            "title"                => "Titre",
            "description"          => "Description",
            "background_image"     => "Image",
            "online_registration"  => "Réalisez votre inscription en ligne en cliquant sur le bouton correspondant à votre situation ci-dessous.",
            "first_registration"   => "Première inscription",
            "renewal_registration" => "Renouvellement",
            "price"                => [
                "label"      => "Libellé",
                "price"      => "Tarif",
                "activation" => "Activation",
            ],
        ],
    ],
    
    // messages
    "message" => [
        "content" => [
            "update" => [
                "success" => "La page d'inscription a bien été mise à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour de la page d'inscription.",
            ],
        ],
        "price"   => [
//            "create"     => [
//                "success" => "La diapo a bien été créée.",
//                "failure" => "Une erreur est survenue lors de la création de la diapo.",
//            ],
//            "update"     => [
//                "success" => "La diapo a bien été mise à jour.",
//                "failure" => "Une erreur est survenue lors de la mise à jour de la diapo.",
//            ],
            "find" => [
                "failure" => "La tarif #:id n'existe pas.",
            ],
//            "delete"     => [
//                "success" => "La diapo <b>:slide</b> a été supprimée avec succès.",
//                "failure" => "Une erreur est survenue lors de la suppression de la diapo <b>:slide</b>.",
//            ],
            "activation" => [
                "success" => [
                    "label"  => "Le tarif <b>:price</b> a bien été :action.",
                    "action" => "{0}désactivé|{1}activé",
                ],
                "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du tarif <b>:price</b>",
            ],
        ],
    ],
];