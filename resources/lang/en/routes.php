<?php

return [
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
        "index"   => "users",
        "create"  => "users/create",
        "store"   => "users/store",
        "edit"    => "users/edit/{id}",
        "update"  => "users/update",
        "profile" => "my-profile",
        "destroy" => "users/destroy",
    ],
    "logout"      => "logout",
];