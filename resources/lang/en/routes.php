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
        "email"      => "my-account/activation-email",
        "activation" => "my-account/activation",
    ],
    "password"    => [
        "index"  => "password/forgotten",
        "update" => "password/update",
        "email"  => "mot-de-passe/reset-email",
        "reset"  => "mot-de-passe/reset",
    ],

    // back
    // routes
    "dashboard"   => [
        "index" => "admin/dashboard",
    ],
    "settings"    => [
        "index"  => "admin/settings",
        "update" => "admin/settings/update",
    ],
    "permissions" => [
        "index"   => "admin/permissions",
        "create"  => "admin/permissions/create",
        "store"   => "admin/permissions/store",
        "edit"    => "admin/permissions/edit/{id}",
        "update"  => "admin/permissions/update",
        "destroy" => "admin/permissions/destroy",
    ],
    "users"       => [
        "index"    => "admin/users",
        "create"   => "admin/users/create",
        "store"    => "admin/users/store",
        "edit"     => "admin/users/edit/{id}",
        "update"   => "admin/users/update",
        "profile"  => "admin/my-profile",
        "destroy"  => "admin/users/destroy",
        "activate" => "admin/users/activate",
    ],
    "home"        => [
        "edit" => "admin/contents/home-page/edition",
    ],
    "slide"       => [
        "create"  => "admin/contents/home-page/diapo/creation",
        "store"   => "admin/contents/home-page/diapo/enregistrement",
        "edit"    => "admin/contents/home-page/diapo/edition/{id}",
        "update"  => "admin/contents/home-page/diapo/mise-a-jour",
        "destroy" => "admin/contents/home-page/diapo/suppression",
    ],
    "logout"      => "admin/logout",
];