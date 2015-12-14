<?php

return [

    // settings
    "settings"           => "Paramètres",
    "settings.view"      => "Consulter les paramètres",
    "settings.update"    => "Modifier les paramètres",

    // permissions
    "permissions"        => "Permissions utilisateur",
    "permissions.list"   => "Consulter la liste des permissions utilisateur",
    "permissions.view"   => "Consulter le détail d'une permission utilisateur",
    "permissions.create" => "Créer une permission utilisateur",
    "permissions.update" => "Modifier une permission utilisateur",
    "permissions.delete" => "Supprimer une permission utilisateur",

    // users
    "users"              => "Utilisateurs",
    "users.list"         => "Consulter la liste des utilisateurs",
    "users.create"       => "Créer un utilisateur",
    "users.view"         => "Consulter le détail d'un utilisateur",
    "users.update"       => "Modifier un utilisateur",
    "users.delete"       => "Supprimer un utlisateur",

    // home
    "home"               => "Page d'accueil",
    "home.edit"          => "Consulter le détail de la page d'accueil",
    "home.update"        => "Modifier la page d'accueil",
    "home.slide.create"  => "Créer une diapo",
    "home.slide.view"    => "Consulter le détail d'une diapo",
    "home.slide.update"  => "Modifier une diapo",
    "home.slide.delete"  => "Supprimer une diapo",

    // partners
    "partners"           => "Partenaires",
    "partners.list"      => "Consulter la liste des partenaires",
    "partners.create"    => "Créer un partenaire",
    "partners.view"      => "Consulter le détail d'un partenaire",
    "partners.update"    => "Modifier un partenaire",
    "partners.delete"    => "Supprimer un partenaire",

    // pages
    "page"               => [
        "title"  => [
            "management"  => "Gestion des permissions utilisateur",
            "list"        => "Liste des rôles",
            "create"      => "Création d'un rôle utlisateur",
            "edit"        => "Édition d'un rôle utilisateur",
            "info"        => "Informations du rôle",
            "permissions" => "Permissions du rôle",
        ],
        "action" => [
            "edit"   => "Éditer le rôle",
            "create" => "Créer le rôle",
            "delete" => "Supprimer le rôle",
        ],
        'info'   => [
            "rank" => "Configurez la position hiérarchique du rôle actuel en choisissant son supérieur direct.",
        ],
        "label"  => [
            "placeholder" => "--- Séléctionnez le rôle parent ---",
            "master"      => "Premier rôle (pas de rôle parent)",
            "name"        => "Nom du rôle",
            "slug"        => "Clé du rôle",
            "rank"        => "Rang",
            "parent_role" => "Rôle parent",
            "created_at"  => "Date de création",
            "updated_at"  => "Date de modification",
        ],
    ],

    // messages
    "message"            => [
        "access" => [
            "denied" => "Vous n'avez pas l'autorisation d'effectuer cette action",
        ],
        "create" => [
            "success" => "Le rôle <b>:name</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création de l'utilisateur <b>:name</b>.",
        ],
        "update" => [
            "success" => "Le rôle <b>:name</b> a bien été mis à jour.",
            "failure" => "Une erreur est survenue lors de la mise à jour de l'utilisateur <b>:name</b>.",
        ],
        "find"   => [
            "failure" => "Le rôle #:id n'existe pas.",
        ],
        "delete" => [
            "success" => "Le rôle <b>:name</b> a bien été supprimé.",
            "failure" => "Une erreur est survenue lors de la mise à jour du rôle <b>:name</b>.",
        ],
        "rank"   => [
            "denied" => "Vous n'avez pas droits nécessaire pour :action un utilisateur avec un rôle hiérarchiquement supérieur au votre.",
            "action" => [
                "edit"   => "éditer",
                "delete" => "supprimer",
            ],
        ],
    ],

];