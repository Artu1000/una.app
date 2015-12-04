<?php

return [

    // settings
    "settings"           => "Settings",
    "settings.view"      => "View settings",
    "settings.update"    => "Edit settings",

    // permissions
    "permissions"        => "User permissions",
    "permissions.list"   => "View the user permissions list",
    "permissions.view"   => "View user permissions details",
    "permissions.create" => "Create a user permission",
    "permissions.update" => "Edit a user permission",
    "permissions.delete" => "Delete a user permission",

    // users
    "users"              => "Users",
    "users.list"         => "View the users list",
    "users.create"       => "Create a user",
    "users.view"         => "View users details",
    "users.update"       => "Edit a user",
    "users.delete"       => "Delete a user",

    // home
    "home"               => "Home page",
    "home.edit"          => "View the home page details",
    "home.update"        => "Update the home page",
    "home.slide.create"  => "Create a slide",
    "home.slide.view"    => "View slide details",
    "home.slide.update"  => "Update a slide",
    "home.slide.delete"  => "Delete a slide",

    // pages
    "page"               => [
        "title"  => [
            "management"  => "User permissions management",
            "list"        => "User roles list",
            "create"      => "User role creation",
            "edit"        => "User role edition",
            "info"        => "Role data",
            "permissions" => "Role permissions",
        ],
        "action" => [
            "edit"   => "Edit the role",
            "create" => "Create the role",
            "delete" => "Remove the role",
        ],
        'info'   => [
            "rank" => "Configure the hierarchical position of the current role by choosing its immediate superior.",
        ],
        "label"  => [
            "placeholder" => "--- Select the parent role ---",
            "master"      => "Main role (no parent role)",
            "name"        => "Role name",
            "slug"        => "Role slug",
            "rank"        => "Rank",
            "parent_role" => "Parent role",
            "created_at"  => "Creation date",
            "updated_at"  => "Modification date",
        ],
    ],

    // messages
    "message"            => [
        "access" => [
            "denied" => "You don't have the authorization to execute this action",
        ],
        "create" => [
            "success" => "The role <b>:name</b> has been created with success.",
            "failure" => "An error occurred during the creation of the role <b>:name</b>.",
        ],
        "update" => [
            "success" => "The role <b>:name</b> has been updated with success.",
            "failure" => "An error occurred during the edition of the role <b>:name</b>.",
        ],
        "find"   => [
            "failure" => "The role #:id doesn't exits.",
        ],
        "delete" => [
            "success" => "The role <b>:name</b> has been deleted with success.",
            "failure" => "An error occurred during the removal of the role <b>:name</b>.",
        ],
        "rank"   => [
            "denied" => "You don't have the permission to :action a user with a role hierarchically superior than yours.",
            "action" => [
                "edit"   => "edit",
                "delete" => "delete",
            ],
        ],
    ],

];