<?php

return [

    // front
    // routes
    "login"        => [
        "index" => "espace-connexion",
        "login" => "espace-connexion/connexion",
    ],
    "account"      => [
        "create"     => "mon-compte/creation",
        "store"      => "mon-compte/enregistrement",
        "email"      => "mon-compte/email-activation",
        "activation" => "mon-compte/activation",
    ],
    "password"     => [
        "index"  => "mot-de-passe/oublie",
        "update" => "mot-de-passe/mise-a-jour",
        "email"  => "mot-de-passe/email-reinitialisation",
        "reset"  => "mot-de-passe/reinitialisation",
    ],
    "leading_team" => [
        "index" => "equipe-dirigeante",
    ],
    "palmares"     => [
        "index" => "palmares",
    ],
    "calendar"     => [
        "index" => "calendrier",
    ],
    "e-shop"       => [
        "index"       => "boutique-en-ligne",
        "add-to-cart" => "boutique-en-ligne/ajout-au-panier",
    ],
    "page"         => [
        "show" => 'page/{key}',
    ],
    "sitemap"      => [
        "index" => "sitemap.xml",
    ],
    "rss"          => [
        "index" => "rss",
    ],

    // back
    // routes
    "dashboard"    => [
        "index" => "admin/tableau-de-bord",
    ],
    "settings"     => [
        "index"  => "admin/parametres",
        "update" => "admin/parametres/mise-a-jour",
    ],
    "permissions"  => [
        "index"   => "admin/permissions",
        "create"  => "admin/permissions/creation",
        "store"   => "admin/permissions/enregistrement",
        "edit"    => "admin/permissions/edition/{id}",
        "update"  => "admin/permissions/mise-a-jour/{id}",
        "destroy" => "admin/permissions/suppression/{id}",
    ],
    "users"        => [
        "index"    => "admin/utilisateurs",
        "create"   => "admin/utilisateurs/creation",
        "store"    => "admin/utilisateurs/enregistrement/{id}",
        "edit"     => "admin/utilisateurs/edition/{id}",
        "update"   => "admin/utilisateurs/mise-a-jour/{id}",
        "destroy"  => "admin/utilisateurs/suppression/{id}",
        "profile"  => "admin/mon-profil",
        "activate" => "admin/utilisateurs/activation/{id}",
    ],
    "home"         => [
        "edit"   => "admin/contenus/page-accueil/edition",
        "update" => "admin/contenus/page-accueil/mise-a-jour",
    ],
    "slides"       => [
        "create"   => "admin/contenus/page-accueil/diapo/creation",
        "store"    => "admin/contenus/page-accueil/diapo/enregistrement/{id}",
        "edit"     => "admin/contenus/page-accueil/diapo/edition/{id}",
        "update"   => "admin/contenus/page-accueil/diapo/mise-a-jour/{id}",
        "destroy"  => "admin/contenus/page-accueil/diapo/suppression/{id}",
        "activate" => "admin/contenus/page-accueil/diapo/activation/{id}",
    ],
    "news"         => [
        "index"    => "actus",
        "show"     => "actus/{id}/{key}",
        "list"     => "admin/contenus/actus",
        "create"   => "admin/contenus/actus/creation",
        "store"    => "admin/contenus/actus/enregistrement",
        "edit"     => "admin/contenus/actus/edition/{id}",
        "update"   => "admin/contenus/actus/mise-a-jour/{id}",
        "destroy"  => "admin/contenus/actus/suppression/{id}",
        "activate" => "admin/contenus/actus/activation/{id}",
    ],
    "schedules"    => [
        "index"       => "horaires",
        "list"        => "admin/contenus/horaires",
        "create"      => "admin/contenus/horaires/creation",
        "store"       => "admin/contenus/horaires/enregistrement",
        "edit"        => "admin/contenus/horaires/edition/{id}",
        "update"      => "admin/contenus/horaires/mise-a-jour/{id}",
        "data_update" => "admin/contenus/horaires/donnees/mise-a-jour",
        "destroy"     => "admin/contenus/horaires/suppression/{id}",
        "activate"    => "admin/contenus/horaires/activation/{id}",
    ],
    "registration" => [
        "index"  => "inscription",
        "page"   => [
            "edit"   => "admin/contenus/inscription",
            "update" => "admin/contenus/inscription/mise-a-jour",
        ],
        "prices" => [
            "create"   => "admin/contenus/inscription/tarifs/creation",
            "store"    => "admin/contenus/inscription/tarifs/enregistrement",
            "edit"     => "admin/contenus/inscription/tarifs/edition/{id}",
            "update"   => "admin/contenus/inscription/tarifs/mise-a-jour/{id}",
            "destroy"  => "admin/contenus/inscription/tarifs/suppression/{id}",
            "activate" => "admin/contenus/inscription/tarifs/activation/{id}",
        ],
    ],
    "partners"     => [
        "index"    => "admin/contenus/partenaires",
        "create"   => "admin/contenus/partenaires/creation",
        "store"    => "admin/contenus/partenaires/enregistrement",
        "edit"     => "admin/contenus/partenaires/edition/{id}",
        "update"   => "admin/contenus/partenaires/mise-a-jour/{id}",
        "destroy"  => "admin/contenus/partenaires/suppression/{id}",
        "activate" => "admin/contenus/partenaires/activation/{id}",
    ],
    "logout"       => "admin/deconnexion",
];