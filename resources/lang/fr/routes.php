<?php

return [
    "dashboard"   => [
        "index" => "tableau-de-bord",
    ],
    "account"     => [
        "index" => "mon-profil",
    ],
    "settings"    => [
        "index"  => "parametres",
        "update" => "parametres/mise-a-jour",
    ],
    "permissions" => [
        "index"   => "permissions",
        "create"  => "permissions/creation",
        "store"   => "permissions/enregistrement",
        "edit"    => "permissions/edition/{id}",
        "update"  => "permissions/mise-a-jour",
        "destroy" => "permissions/suppression",
    ],
    "users"       => [
        "index"    => "users",
        "create"   => "users/creation",
        "store"    => "users/enregistrement",
        "edit"     => "users/edition/{id}",
        "update"   => "users/mise-a-jour",
        "destroy"  => "users/suppression",
        "profile"  => "mon-profil",
        "activate" => "users/activate",
    ],
    "logout"      => "deconnexion",
];