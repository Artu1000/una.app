<?php

return [
    
    // views
    "page"    => [
        "title"  => [
            "management"          => "Gestion des tarifs d'inscription",
            "content"             => "Contenu",
            "online_registration" => "Inscription en ligne",
            "prices"              => "Nos tarifs",
            "price"               => [
                "list"   => "Liste des tarifs",
                "create" => "Création d'un tarif",
                "edit"   => "Édition du tarif <b>:price</b>",
                "data"   => "Données du tarif",
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
            "creation"   => [
                "success" => "Le tarif d'inscription <b>:price</b> a bien été créée.",
                "failure" => "Une erreur est survenue lors de la création du tarif d'inscription <b>price</b>.",
            ],
            "update"     => [
                "success" => "Le tarif d'inscription <b>:price</b> a bien été mis à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour du tarif d'inscription <b>:price</b>.",
            ],
            "find"       => [
                "failure" => "La tarif d'inscription #:id n'existe pas.",
            ],
            "delete"     => [
                "success" => "La tarif d'inscription <b>:price</b> a été supprimée avec succès.",
                "failure" => "Une erreur est survenue lors de la suppression du tarif d'inscription <b>:price</b>.",
            ],
            "activation" => [
                "success" => [
                    "label"  => "Le tarif d'inscription <b>:price</b> a bien été :action.",
                    "action" => "{0}désactivé|{1}activé",
                ],
                "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du tarif d'inscription <b>:price</b>",
            ],
        ],
    ],
];