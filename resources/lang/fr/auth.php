<?php

return [
    // login
    "login"   => [
        "title"  => "Espace connexion",
        "label"  => [
            "email"              => "Adresse e-mail",
            "password"           => "Mot de passe",
            "remember_me"        => "Se souvenir de moi",
            "forgotten_password" => "Mot de passe oublié",
            "create_account"     => "Créer un compte",
        ],
        "action" => [
            "login" => "Me connecter",
            "back"  => "Retour au site",
        ],
    ],

    // account creation
    "account_creation" => [
        "title" => "Créer un compte",
        "label" => [
            "last_name" => "NOM",
            "first_name" => "Prénom",
            "email" => "Adresse e-mail",
            "password" => "Mot de passe",
            "password_confirmation" => "Confirmation du mot de passe",
        ],
        "action" => [
            "create" => "Créer mon compte"
        ]
    ],

    // messages
    "message" => [
        "login"      => [
            "success" => "Bienvenue <b>:name</b>, vous êtes maintenant connecté.",
            "failure" => "<b>E-mail</b> ou <b>mot de passe</b> erroné. Veuillez rééssayer.",
            "error"   => "Une erreur est survenue lors de votre authentification.",
        ],
        "logout"     => [
            "success" => "Au revoir <b>:name</b>, vous avez bien été déconnecté.",
            "failure" => "Désolé <b>:name</b>, une erreur est survenue lors de votre déconnexion.",
        ],
        "activation" => [
            "success" => "Félicitations <b>:name</b>, votre compte est activé. Vous pouvez maintenant vous y connecter.",
            "failure" => "Votre compte n'est pas activé. Activez-le à partir du lien qui vous a été transmis par e-mail lors de votre inscription. ",
            "error"   => "Une erreur est survenue pendant l'activation de votre compte.",
            "email"   => [
                "success" => "Un e-mail vous permettant d'activer votre compte vous a été envoyé à l'adresse <b>:email</b>. Vous le recevrez dans quelques instants.",
                "failure" => "Une erreur est survenue lors de l'envoi de votre e-mail d'activation.",
                "resend"  => "Pour recevoir un nouvel e-mail d'activation à l'addresse <b>:email</b>, <a href=':url' title=\"Me renvoyer un nouvel email d'activation\" class='underline'>cliquez ici</a>.",
            ],
            "token"   => [
                "expired" => "Votre jeton d'activation est invalide ou a expiré.",
                "resend"  => "Pour recevoir un nouveau jeton d'activation à l'addresse <b>:email</b>, <a href=':url' title=\"Me renvoyer un nouveau jeton d\'activation\" class='underline'>cliquez ici</a>.",
            ],
        ],
        "find"       => [
            "failure" => "Aucun utilisateur correspondant à l'adresse e-mail <b>:email</b> n'a été trouvé.",
        ],
        "throttle"   => [
            "ip" => "En raison d'erreurs répétées, l'accès à l'application depuis votre IP est suspendu pendant <b>:seconds</b> secondes.",
        ],
        "account_creation" => [
            "success" => "Votre compte personnel a bien été créé."
        ]
    ],
];