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
        "update"  => "admin/permissions/update/{id}",
        "destroy" => "admin/permissions/destroy/{id}",
    ],
    "users"       => [
        "index"    => "admin/users",
        "create"   => "admin/users/create",
        "store"    => "admin/users/store",
        "edit"     => "admin/users/edit/{id}",
        "update"   => "admin/users/update",
        "profile"  => "admin/my-profile",
        "destroy"  => "admin/users/destroy/{id}",
        "activate" => "admin/users/activate/{id}",
    ],
    "logout"      => "admin/logout",
];