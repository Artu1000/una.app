<?php

return [
    "last_name" => "NOM",
    "first_name" => "Prénom",
    "status" => "Statut",
    "board" => "Organe",
    "role" => "Permissions",
    "activation" => "Activation",

    "view" => [
        "action" => [
            "update" => "Éditer l'utilisateur",
            "create" => "Créer l'utilisateur",
        ],
        'info' => [
            "photo" => "Formats acceptés : jpg, jpeg, png.",
            "phone_number" => "Numéro français uniquement.",
            "password" => "Ne remplir que si vous souhaitez modifier le mot de passe actuel de l'utilisateur."
        ],
        "title" => [
            "personal_data" => "Données personnelles",
            "contact" => "Contact",
            "security" => "Sécurité"
        ],
        "label" => [
            "photo" => "Photo",
            "gender" => "Genre",
            "last_name" => "NOM",
            "first_name" => "Prénom",
            "birth_date" => "Date de naissance",
            "phone_number" => "Numéro de téléphone",
            "email" => "Adresse e-mail",
            "address" => "Adresse postal",
            "zip_code" => "Code postal",
            "city" => "Ville",
            "country" => "Pays",
            "new_password" => "Nouveau mot de passe",
            "password_confirm" => "Confirmation du nouveau mot de passe",
        ],
    ],

    "message" => [
        "creation" => [
            "success" => "L'utilisateur <b> :name </b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création de l'utilisateur <b>:name</b>.",
        ],
        "update" => [
            "success" => "L'utilisateur <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'utilisateur <b>:name</b>."
        ],
        "find" => [
            "failure" => "L'utilisateur sélectionné n'existe pas."
        ],
        "delete" => [
            "success" => "Le rôle <b>:name</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'utilisateur <b>:name</b>."
        ],
        "activation" => [
            "success" => "Le statut d'activation de l'utilisateur <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue de la mise à jour du statur d'activation de l'utilisateur <b>:name</b>"
        ]
    ]
];