<?php

return [
    // login
    "login"   => [
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
            "back"    => "Back to the website",
        ],
    ],

    // messages
    "message" => [
        "login" => [
            "success" => "Welcome <b>:name</b>, you are now logged in.",
            "failure" => "Wrong <b>e-mail</b> or <b>password</b>. Please try again.",
            "error" => "An error occured during your authentification."
        ],
        "logout" => [
            "success" => "Goodbye <b>:name</b>, you have been logged out with success.",
            "failure" => "Sorry <b>:name</b>, an error occured during your logout process.",
        ],
        "activation" => [
            "failure" => "Your account is not activated. To do so, click on the link you received by e-mail following your account creation. ",
            "resend_email" => "To receive a new activation e-mail, <a href=':url' title='Send me a new activation email' class='underline'>click here</a>.",
        ],
        "throttle" => [
            "ip" => "Due to repeated login errors, your IP is suspended from the app during <b>:seconds</b> secondes.",
        ]
    ],
];