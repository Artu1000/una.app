<?php

return [
    
    // views
    "page"    => [
        "title"  => [
            "management"          => "Gestion de la page d'inscription",
            "content"             => "Contenu",
            "online_registration" => "Inscription en ligne",
            "registration_form"   => "Formulaire d'inscription",
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
            "background_image"       => "Largeur min : 2560px / Hauteur min : 1440px / Formats : jpg, jpeg.",
            "registration_form_file" => "Format : pdf.",
        ],
        "label"  => [
            "title"                      => "Titre",
            "description"                => "Description",
            "background_image"           => "Image de fond",
            "remove_background_image"    => "Supprimer l'image de fond",
            "registration_form"          => "Pour vous inscrire ou renouveler votre licence, et uniquement si l'inscription en ligne ne marche pas dans votre cas, téléchargez (ci-dessous) notre formulaire d'inscription et déposez-le rempli club, accompagné des pièces demandées.",
            "online_registration"        => "Réalisez votre inscription en ligne en cliquant sur le bouton correspondant à votre situation ci-dessous.",
            "first_registration"         => "Première inscription",
            "renewal_registration"       => "Renouvellement",
            "registration_form_file"     => "Formulaire d'inscription (fichier)",
            "registration_form_download" => "Télécharger le formulaire d'inscription",
            "price"                      => [
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
