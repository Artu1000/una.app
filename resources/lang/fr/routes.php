<?php

return [

    // front
    // routes
    "login"       => [
        "index" => "espace-connexion",
        "login" => "espace-connexion/connexion",
    ],
    "account"     => [
        "create"     => "mon-compte/creation",
        "store"      => "mon-compte/enregistrement",
        "email"      => "mon-compte/envoi-email-activation",
        "activation" => "mon-compte/activation",
    ],

    // back
    // prefixes
    "admin"       => "admin",
    "contents"    => "contenus",
    // routes
    "dashboard"   => [
        "index" => "tableau-de-bord",
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
    "logout"      => "deconnexion",
];