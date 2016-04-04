<?php

return [
    
    // config
    "config"  => [
        "category"    => [
            "all_publics"   => "Tous publics",
            "suaps"         => "Étudiants / SUAPS",
            "rowing_school" => "École d'Aviron (18 ans et moins)",
            "competition"   => "Compétition",
        ],
        "day_of_week" => [
            "monday"    => "Lundi",
            "tuesday"   => "Mardi",
            "wednesday" => "Mercredi",
            "thursday"  => "Jeudi",
            "friday"    => "Vendredi",
            "saturday"  => "Samedi",
            "sunday"    => "Dimanche",
        ],
    ],
    
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
            "background_image" => "Largeur min : 2560px - Hauteur min : 1440px - Format : jpg.",
            "description"      => "Longueur minimale de la description : 250 caractères.",
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
            "search"                      => "Jour, Heure début, Heure fin, Catégorie",
            "background_image"            => "Image de fond",
            "remove_background_image"     => "Supprimer l'image de fond",
            "legend"                      => "Légende",
        ],
    ],
    
    // messages
    "message" => [
        "content_update" => [
            "success" => "Le contenu de la page <b>:title</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour du contenu de la page <b>:title</b>.",
        ],
        "creation"       => [
            "success" => "Le créneau horaire <b>:schedule</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création du créneau horaire <b>:schedule</b>.",
        ],
        "update"         => [
            "success" => "Le créneau horaire <b>:schedule</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour du créneau horaire <b>:schedule</b>.",
        ],
        "find"           => [
            "failure" => "Le créneau horaire #:id n'existe pas.",
        ],
        "delete"         => [
            "success" => "Le créneau horaire <b>:schedule</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression du créneau horaire <b>:schedule</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "Le créneau horaire <b>:schedule</b> a bien été :action.",
                "action" => "{0}désactivé|{1}activé",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation du créneau horaire <b>:schedule</b>",
        ],
    ],
];