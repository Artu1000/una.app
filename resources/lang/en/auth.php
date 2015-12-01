<?php

return [
    // login
    "login"            => [
        "title"  => "Login area",
        "label"  => [
            "email"              => "E-mail address",
            "password"           => "Password",
            "remember_me"        => "Remember me",
            "forgotten_password" => "Forgotten password",
            "create_account"     => "Create an account",
        ],
        "action" => [
            "login" => "Log in",
            "back"  => "Back to the website",
        ],
    ],

    // account creation
    "account_creation" => [
        "title"  => "Create an account",
        "label"  => [
            "last_name"             => "Last name",
            "first_name"            => "First name",
            "email"                 => "E-mail",
            "password"              => "Password",
            "password_confirmation" => "Password confirmation",
        ],
        "action" => [
            "create" => "Create my account",
        ],
    ],

    // password
    "password"         => [
        "forgotten" => [
            "title"  => "Forgotten password",
            "label"  => [
                "email" => "E-mail address",
            ],
            "info"   => [
                "instructions" => "Please enter your e-mail to receive your password reset instructions.",
            ],
            "action" => [
                "send" => "Send me the e-mail",
            ],
        ],
        "reset"     => [
            "title"  => "Réinitialisation",
            "label"  => [
                "password"              => "Nouveau mot de passe",
                "password_confirmation" => "Confirmation du nouveau mot de passe",
            ],
            "info"   => [
                "instructions" => "Veuillez créer un nouveau mot de passe afin de remplacer celui que vous avez perdu.",
            ],
            "action" => [
                "save" => "Modifier mon mot de passe",
            ],
        ],
    ],

    // messages
    "message"          => [
        "login"            => [
            "success" => "Welcome <b>:name</b>, You have been logged in with success.",
            "failure" => "Wrong <b>E-mail</b> or <b>password</b>. Please try again.",
            "error"   => "An error occured during your authentification.",
        ],
        "logout"           => [
            "success" => "Goodbye <b>:name</b>, you have been logged out with success.",
            "failure" => "Sorry <b>:name</b>, an error occured during your logout process.",
        ],
        "activation"       => [
            "success" => "Congratulations <b>:name</b>, your account have been activated. You can now log yourself in.",
            "failure" => "Your account is deactivated. Please activate it from the activation e-mail you received following your account creation.",
            "error"   => "An error occured during your accont activation.",
            "email"   => [
                "success" => "An e-mail regarding your account activation has been sent to <b>:email</b>. You should receive it in a few moment.",
                "failure" => "An error occured during the sending of your activation e-mail.",
                "resend"  => "To receive a new activation email at <b>:email</b>, <a href=':url' title=\"Send me a new activation e-mail\" class='underline'>click here</a>.",
            ],
            "token"   => [
                "expired" => "Your activation token is invalid or has expired.",
                "resend"  => "To receive a new activation token at <b>:email</b>, <a href=':url' title=\"Send me a new activation token\" class='underline'>click here</a>.",
            ],
        ],
        "password_reset"   => [
            "success" => "Your new password has been saved with success. Your can now log yourself in.",
            "failure" => "An error occured during the save process of your new password.",
            "email"   => [
                "success" => "An e-mail containing all the instructions regarding the reset of your password has been sent to <b>:email</b>.",
                "failure" => "An error occured during the sending of your reset e-mail.",
            ],
            "token"   => [
                "expired" => "Your reset token is invalid or has expired. Please renew the password reset operation.",
            ],
        ],
        "find"             => [
            "failure" => "There is no user associated to the e-mail <b>:email</b>.",
        ],
        "throttle"         => [
            "ip" => "Due to repeated login failures, your IP has been suspended for <b>:seconds</b> seconds.",
        ],
        "account_creation" => [
            "success" => "Your personal account has been created with success.",
        ],
    ],

];