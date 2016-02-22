<?php

return [

    //scoring form
    'scoring_form'             => "Districts scoring form",
    'scoring_form.create'      => "Fill the districts scoring form for the first time",
    'scoring_form.update'      => "Update the districts scoring form data",
    'scoring_form.any_country' => "Fill / update the scoring form data for the districts from all countries (and not only for the districts from the country the user belongs)",
    'scoring_form.any_year'    => "Fill / update the scoring form data for every years (and not only for the current year)",
    "scoring_form.logs"        => "Access to the districts scoring form logs",

    // settings
    "settings"           => "Settings",
    "settings.view"      => "View settings",
    "settings.update"    => "Edit settings",

    // permissions
    "permissions"        => "Roles",
    "permissions.list"   => "View the roles list",
    "permissions.view"   => "View roles details",
    "permissions.create" => "Create a role",
    "permissions.update" => "Edit a role",
    "permissions.delete" => "Delete a role",

    // users
    "users"              => "Users",
    "users.list"         => "View the users list",
    "users.create"       => "Create a user",
    "users.view"         => "View users details",
    "users.update"       => "Edit a user",
    "users.delete"       => "Delete a user",

    // pages
    "page"               => [
        "title"  => [
            "management"  => "Roles management",
            "list"        => "Roles list",
            "create"      => "Role creation",
            "edit"        => "Edition of the role <b>:role</b>",
            "info"        => "Role data",
            "permissions" => "Role permissions",
        ],
        "action" => [
            "edit"   => "Edit the role",
            "create" => "Create the role",
            "delete" => "Remove the role",
        ],
        'info'   => [
            "position" => "Configure the hierarchical position of the current role by choosing its immediate superior.",
        ],
        "label"  => [
            "placeholder" => "--- Select the parent role ---",
            "master"      => "Main role (no parent role)",
            "name"        => "Role name",
            "slug"        => "Role slug",
            "position"    => "Position",
            "parent_role" => "Parent role",
            "created_at"  => "Creation date",
            "updated_at"  => "Modification date",
        ],
    ],

    // messages
    "message"            => [
        "access"   => [
            "denied" => "You don't have the authorization to execute this action",
        ],
        "creation" => [
            "success" => "The role <b>:name</b> has been created with success.",
            "failure" => "An error occurred during the creation of the role <b>:name</b>.",
        ],
        "update"   => [
            "success" => "The role <b>:name</b> has been updated with success.",
            "denied"  => "You can't delete your own following permissions :",
            "failure" => "An error occurred during the edition of the role <b>:name</b>.",
        ],
        "find"     => [
            "failure" => "The role <b>#:id</b> doesn't exits.",
        ],
        "delete"   => [
            "success" => "The role <b>:name</b> has been deleted with success.",
            "denied"  => "You can't delete the role <b>:name</b> because your are attached to it currently.",
            "failure" => "An error occurred during the removal of the role <b>:name</b>.",
        ],
    ],
];