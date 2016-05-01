<?php

return [

    // settings
    "settings"                   => "Paramètres",
    "settings.view"              => "Consulter les paramètres",
    "settings.update"            => "Modifier les paramètres",

    // permissions
    "permissions"                => "Permissions utilisateur",
    "permissions.list"           => "Consulter la liste des permissions utilisateur",
    "permissions.view"           => "Consulter le détail d'une permission utilisateur",
    "permissions.create"         => "Créer une permission utilisateur",
    "permissions.update"         => "Modifier une permission utilisateur",
    "permissions.delete"         => "Supprimer une permission utilisateur",

    // users
    "users"                      => "Utilisateurs",
    "users.list"                 => "Consulter la liste des utilisateurs",
    "users.create"               => "Créer un utilisateur",
    "users.view"                 => "Consulter le détail d'un utilisateur",
    "users.update"               => "Modifier un utilisateur",
    "users.delete"               => "Supprimer un utlisateur",

    // home
    "home"                       => "Page d'accueil",
    "home.page.view"             => "Consulter le détail de la page d'accueil",
    "home.page.update"           => "Modifier le contenu de la page d'accueil",
    "home.slides.create"         => "Créer une diapo",
    "home.slides.view"           => "Consulter le détail d'une diapo",
    "home.slides.update"         => "Modifier une diapo",
    "home.slides.delete"         => "Supprimer une diapo",

    // news
    "news"                       => "Actualités",
    "news.page.view"             => "Consulter le détail de la page d'actualités",
    "news.page.update"           => "Modifer le contenu de la page d'actualités",
    "news.create"                => "Créer une actualité",
    "news.view"                  => "Consulter le détail d'une actualité",
    "news.update"                => "Modifier une actualité",
    "news.delete"                => "Supprimer une actualité",
    "news.activate"              => "Activer une actualité",

    // schedules
    "schedules"                  => "Horaires",
    "schedules.page.view"        => "Consulter le détail de la page des créneaux horaires",
    "schedules.page.update"      => "Modifier le contenu de la page des créneaux horaires",
    "schedules.create"           => "Créer un créneau horaire",
    "schedules.view"             => "Consulter le détail d'un créneau horaire",
    "schedules.update"           => "Modifier un créneau horaire",
    "schedules.delete"           => "Supprimer un créneau horaire",

    // registration
    "registration"               => "Inscription",
    "registration.page.view"     => "Consulter le détail de la page d'inscription",
    "registration.page.update"   => "Modifier le contenu de la page d'inscription",
    "registration.prices.create" => "Créer un tarif d'inscription",
    "registration.prices.view"   => "Consulter le détail d'un tarif d'inscription",
    "registration.prices.update" => "Modifier un tarif d'inscription",
    "registration.prices.delete" => "Supprimer un tarif d'inscription",

    // partners
    "partners"                   => "Partenaires",
    "partners.list"              => "Consulter la liste des partenaires",
    "partners.create"            => "Créer un partenaire",
    "partners.view"              => "Consulter le détail d'un partenaire",
    "partners.update"            => "Modifier un partenaire",
    "partners.delete"            => "Supprimer un partenaire",

    // pages
    "page"                       => [
        "title"  => [
            "management"  => "Gestion des rôles",
            "list"        => "Liste des rôles",
            "create"      => "Création d'un rôle",
            "edit"        => "Édition du rôle <b>:role</b>",
            "info"        => "Informations du rôle",
            "permissions" => "Permissions du rôle",
        ],
        "action" => [
            "edit"   => "Éditer le rôle",
            "create" => "Créer le rôle",
            "delete" => "Supprimer le rôle",
        ],
        'info'   => [
            "position" => "Configurez la position hiérarchique du rôle actuel en choisissant son supérieur direct.",
        ],
        "label"  => [
            "placeholder" => "--- Séléctionnez le rôle parent ---",
            "master"      => "Premier rôle (pas de rôle parent)",
            "name"        => "Nom",
            "slug"        => "Clé",
            "position"    => "Hiérarchie",
            "parent_role" => "Rôle parent",
            "created_at"  => "Date de création",
            "updated_at"  => "Date de modification",
        ],
    ],

    // messages
    "message"                    => [
        "access"   => [
            "denied" => "Vous n'avez pas l'autorisation d'effectuer cette action : <b>:permission</b>",
        ],
        "creation" => [
            "success" => "Le rôle <b>:name</b> a bien été créé.",
            "failure" => "Une erreur est survenue lors de la création du rôle <b>:name</b>.",
        ],
        "update"   => [
            "success" => "Le rôle <b>:name</b> a bien été mis à jour.",
            "denied"  => "Vous ne pouvez pas supprimer vos propres permissions suivantes :",
            "failure" => "Une erreur est survenue lors de la mise à jour du rôle <b>:name</b>.",
        ],
        "find"     => [
            "failure" => "Le rôle <b>#:id</b> n'existe pas.",
        ],
        "delete"   => [
            "success" => "Le rôle <b>:name</b> a bien été supprimé.",
            "denied"  => "Vous ne pouvez pas supprimer le rôle <b>:name</b> car vous l'utilisez actuellement.",
            "failure" => "Une erreur est survenue lors de la mise à jour du rôle <b>:name</b>.",
        ],
    ],
];