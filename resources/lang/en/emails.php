<?php

return [
    // template
    "template"           => [
        "no_reply" => "This email has been sent automatically. Please do not reply.",
        "problem"  => "For any technical problem, please contact the app support team at : <a href='mailto::email'>:email</a>",
    ],

    // account activation
    "account_activation" => [
        "subject" => "Account activation.",
        "title"   => "Activate your account",
        "hello"   => "Dear :name,",
        "content" => "Congratulations, your personnal account has been created with success.<br/> To activate it, click on the button bellow.",
        "button"  => "I activate my account",
    ],

    // password reset
    "password_reset"     => [
        "subject" => "Password reset.",
        "title"   => "Reset your password",
        "hello"   => "Dear :name,",
        "content" => "Access to your password reset page by clicking on the button bellow.",
        "button"  => "I reset my password",
    ],
];
