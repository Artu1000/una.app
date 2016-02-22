<?php

return [

    // config
    "config"  => [
        "board"  => [
            "student-leading-board" => "Bureau des étudiants",
            "leading_board"         => "Bureau",
            "executive_committee"   => "Commité Directeur",
        ],
        "status" => [
            'president'                => "Président",
            'student-president'        => "Président des étudiants",
            'vice-president'           => "Vice-président",
            'secretary-general'        => "Secrétaire général",
            'student-secretary'        => "Secrétaire des étudiants",
            'treasurer'                => "Trésorier",
            'sportive-commission'      => "Trésorier étudiant",
            'communication-commission' => "Commission Communication",
            'equipment-commission'     => "Commission matériel",
            'leisure-commission'       => "Commission loisirs",
            'employee'                 => "Salarié",
            'user'                     => "Sociétaire",
        ],
        "gender" => [
            "male"   => "Homme",
            "female" => "Femme",
        ],
    ],

    // views
    "page"    => [
        "title"  => [
            "management"    => "Gestion des utilisateurs",
            "list"          => "Liste des utilisateurs",
            "profile"       => "Mon profil",
            "edit"          => "Édition d'un utilisateur",
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
            "photo"              => "Photo",
            "gender"             => "Genre",
            "last_name"          => "NOM",
            "first_name"         => "Prénom",
            "status"             => "Statut",
            "status_placeholder" => "--- Séléctionnez un statut ---",
            "board"              => "Instance",
            "board_placeholder"  => "--- Séléctionnez une instance ---",
            "no_board"           => "N'est pas membre d'une instance",
            "birth_date"         => "Date de naissance",
            "phone_number"       => "Numéro de téléphone",
            "email"              => "Adresse e-mail",
            "address"            => "Adresse postal",
            "zip_code"           => "Code postal",
            "city"               => "Ville",
            "country"            => "Pays",
            "role"               => "Rôle",
            "activation"         => "Activation",
            "account"            => "Compte",
            "new_password"       => "Nouveau mot de passe",
            "password_confirm"   => "Confirmation du nouveau mot de passe",
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
            "success" => "Le statut d'activation de l'utilisateur <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue de la mise à jour du statur d'activation de l'utilisateur <b>:name</b>",
        ],
    ],
];