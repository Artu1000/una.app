<?php

return [

    // config
    "config"  => [
        "board"  => [
            "student_leading_board" => "Bureau des étudiants",
            "leading_board"         => "Bureau",
            "executive_committee"   => "Comité Directeur",
        ],
        "status" => [
            'president'                => "Président",
            'student_president'        => "Président des étudiants",
            'vice_president'           => "Vice-président",
            'secretary_general'        => "Secrétaire général",
            'student_secretary'        => "Secrétaire des étudiants",
            'treasurer'                => "Trésorier",
            'student_treasurer'        => "Trésorier étudiant",
            'sportive_commission'      => "Commission sportive",
            'communication_commission' => "Commission communication",
            'equipment_commission'     => "Commission matériel",
            'leisure_commission'       => "Commission loisirs",
            'employee'                 => "Salarié",
            'user'                     => "Sociétaire",
        ],
        "gender" => [
            "male"   => "<i class=\"fa fa-male\"></i> Homme",
            "female" => "<i class=\"fa fa-female\"></i> Femme",
        ],
    ],

    // views
    "page"    => [
        "title"  => [
            "management"    => "Gestion des utilisateurs",
            "list"          => "Liste des utilisateurs",
            "profile"       => "Mon profil",
            "edit"          => "Édition de l'utilisateur <b>:user</b>",
            "create"        => "Création d'un utlisateur",
            "personal_data" => "Données personnelles",
            "club"          => "Infos club",
            "contact"       => "Contact",
            "security"      => "Sécurité",
        ],
        "action" => [
            "update" => "Éditer l'utilisateur",
            "create" => "Créer l'utilisateur",
            "delete" => "Supprimer l'utilisateur",
        ],
        'info'   => [
            "birth_date"   => "Format jj/mm/aaaa.",
            "photo"        => "Formats acceptés : jpg, jpeg, png.",
            "phone_number" => "Numéro français uniquement.",
            "password"     => "Ne remplir que si vous souhaitez modifier le mot de passe actuel.",
        ],
        "label"  => [
            "photo"                 => "Photo",
            "gender"                => "Genre",
            "last_name"             => "NOM",
            "first_name"            => "Prénom",
            "status_id"             => "Statut",
            "status_id_placeholder" => "--- Séléctionnez un statut ---",
            "board_id"              => "Instance",
            "board_id_placeholder"  => "--- Séléctionnez une instance ---",
            "no_board"              => "N'est pas membre d'une instance",
            "birth_date"            => "Date de naissance",
            "phone_number"          => "Numéro de téléphone",
            "email"                 => "Adresse e-mail",
            "address"               => "Adresse postale",
            "zip_code"              => "Code postal",
            "city"                  => "Ville",
            "country"               => "Pays",
            "role"                  => "Rôle",
            "active"                => "Activation",
            "account"               => "Compte",
            "new_password"          => "Nouveau mot de passe",
            "password_confirm"      => "Confirmation du nouveau mot de passe",
            "remove_photo"          => "Supprimer la photo",
        ],
    ],

    // messages
    "message" => [
        "account"    => [
            "success" => "Vos données personnelles ont bien été mises à jour.",
            "failure" => "Une erreur s'est déroulée lors de la mise à jour de vos données personnelles.",
        ],
        "creation"   => [
            "success" => "L'utilisateur <b>:name</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création de l'utilisateur <b>:name</b>.",
        ],
        "update"     => [
            "success" => "L'utilisateur <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'utilisateur <b>:name</b>.",
        ],
        "find"       => [
            "failure" => "L'utilisateur #:id n'existe pas.",
        ],
        "delete"     => [
            "success" => "L'utilisateur <b>:name</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la suppression de l'utilisateur <b>:name</b>.",
        ],
        "activation" => [
            "success" => [
                "label"  => "L'utilisateur <b>:name</b> a bien été :action.",
                "action" => "{0}désactivé|{1}activé",
            ],
            "failure" => "Une erreur est survenue de la mise à jour du statut d'activation de l'utilisateur <b>:name</b>",
        ],
        "permission" => [
            "denied" => "Vous n'avez pas les droits nécessaires pour :action un utilisateur possédant un rôle hiérarchiquement supérieur au votre.",
            "action" => [
                "create" => "créer",
                "edit"   => "éditer",
                "delete" => "supprimer",
            ],
        ],
    ],
];