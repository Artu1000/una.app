<?php

return [
    // actions
    "action"     => [
        "add"      => "Add",
        "edit"     => "Edit",
        "delete"   => "Delete",
        "confirm"  => "Confirm",
        "cancel"   => "Cancel",
        "back"     => "Back",
        "browse"   => "Browse",
        "activate" => "Activate",
        "close"    => "Close",
        "save"     => "Save changes",
    ],

    // messages
    "message"    => [
        "global" => [
            "failure" => [
                "contact" => [
                    "support" => "Please contact the support team if the problem persists : :email",
                ],
            ],
        ],
    ],

    // modals
    'modal'      => [
        'confirm' => [
            'title'    => 'Confirmation request',
            'question' => "Do you confirm the execution of the following actions ?",
        ],
        'alert'   => [
            "title" => [
                "success" => "Sucess",
                "info"    => "Infos",
                "error"   => "Error",
            ],
        ],
    ],

    // table list
    "table_list" => [
        "placeholder" => [
            "search" => "Search",
            "lines"  => "Lines to show",
        ],
        "column"      => [
            "actions" => "Actions",
        ],
        "results"     => [
            "empty"  => "No results were found.",
            "status" => "Results <b>:start</b> to <b>:stop</b> - Total : <b>:total</b>",
        ],
    ],
];