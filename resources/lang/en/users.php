<?php

return [

    // views
    "page"    => [
        "title"  => [
            "profile"       => "My profile",
            "edit"          => "User edition",
            "create"        => "User creation",
            "personal_data" => "Personal data",
            "contact"       => "Contact",
            "security"      => "Security",
        ],
        "action" => [
            "update" => "Edit the user",
            "create" => "Create the user",
            "delete" => "Delete the user"
        ],
        'info'   => [
            "birth_date"   => "Format jj/mm/aaaa.",
            "photo"        => "Accepted formats : jpg, jpeg, png.",
            "phone_number" => "French format only.",
            "password"     => "To fill only if you want to update the current password.",
        ],
        "label"  => [
            "photo"            => "Photo",
            "gender"           => "Gender",
            "last_name"        => "Last name",
            "first_name"       => "First name",
            "status"           => "Status",
            "board"            => "Board",
            "birth_date"       => "Birth date",
            "phone_number"     => "Phone number",
            "email"            => "E-mail",
            "address"          => "Address",
            "zip_code"         => "Zip code",
            "city"             => "City",
            "country"          => "Country",
            "role"             => "Role",
            "activation"       => "Activation",
            "account"          => "Account",
            "new_password"     => "New password",
            "password_confirm" => "New password confirmation",
        ],
    ],

    // messages
    "message" => [
        "account"    => [
            "success" => "Your personal data has been updated with success.",
            "failure" => "An error occured during the update of your personal data.",
        ],
        "creation"   => [
            "success" => "The user <b>:name</b> has been created with success.",
            "failure" => "An error occured during the creation of the user <b>:name</b>.",
        ],
        "update"     => [
            "success" => "The user <b>:name</b> has been updated with success.",
            "failure" => "An error occured during the update of the user <b>:name</b>.",
        ],
        "find"       => [
            "failure" => "The user #:id doesn't exists.",
        ],
        "delete"     => [
            "success" => "The user <b>:name</b> has been deleted with success.",
            "failure" => "An error occured during the removal of the user <b>:name</b>.",
        ],
        "activation" => [
            "success" => "The activation status of the user <b>:name</b> has been updated with success.",
            "failure" => "An error occured during the update of the activation status from the user <b>:name</b>",
        ],
    ],
];