<?php

return [

    // front
    // routes
    "login"       => [
        "index" => "login-area",
        "login" => "login-area/login",
    ],
    "account"     => [
        "create"     => "my-account/create",
        "store"      => "my-account/store",
        "email"      => "my-account/activation-email-send",
        "activation" => "my-account/activation",
    ],

    // back
    // prefixes
    "admin"       => "admin",
    "contents"    => "contenus",
    // routes
    "dashboard"   => [
        "index" => "dashboard",
    ],
    "settings"    => [
        "index"  => "settings",
        "update" => "settings/update",
    ],
    "permissions" => [
        "index"   => "permissions",
        "create"  => "permissions/create",
        "store"   => "permissions/store",
        "edit"    => "permissions/edit/{id}",
        "update"  => "permissions/update",
        "destroy" => "permissions/destroy",
    ],
    "users"       => [
        "index"    => "users",
        "create"   => "users/create",
        "store"    => "users/store",
        "edit"     => "users/edit/{id}",
        "update"   => "users/update",
        "profile"  => "my-profile",
        "destroy"  => "users/destroy",
        "activate" => "users/activate",
    ],
    "home"        => [
        "edit" => "page-d-accueil/edition",
    ],
    "slide"       => [
        "create"  => "diapo/creation",
        "store"   => "diapo/enregistrement",
        "edit"    => "diapo/edition/{id}",
        "update"  => "diapo/mise-a-jour",
        "destroy" => "diapo/suppression",
    ],
    "logout"      => "logout",
];