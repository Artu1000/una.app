<?php

return [
    "admin"       => "admin",
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
        "index"    => "utilisateurs",
        "create"   => "utilisateurs/creation",
        "store"    => "utilisateurs/enregistrement",
        "edit"     => "utilisateurs/edition/{id}",
        "update"   => "utilisateurs/mise-a-jour",
        "destroy"  => "utilisateurs/suppression",
        "profile"  => "mon-profil",
        "activate" => "utilisateurs/activer",
    ],
    "contents"    => "contenus",
    "home"        => [
        "edit" => "page-d-accueil/edition",
    ],
    "slide" => [
        "create"   => "diapo/creation",
        "store"    => "diapo/enregistrement",
        "edit"     => "diapo/edition/{id}",
        "update"   => "diapo/mise-a-jour",
        "destroy"  => "diapo/suppression",
    ],
    "logout"      => "deconnexion",
];