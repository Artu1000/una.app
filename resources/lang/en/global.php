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
                    "support" => "Please contact the support team if the problem persists : <a href='mailto::email' title='Contact the support team' class='underline'>:email</a>",
                ],
            ],
        ],
        "javascript" => [
            "deactivated" => [
                "title" => "Warning",
                "message" => "Your Javascript is currently deactivated on your brower and your navigation is therby degraded.<br/> Thank you to reactivate your Javascript to fully use all the features provided by the application."
            ]
        ]
    ],

    // modals
    'modal'      => [
        'confirm' => [
            'title'    => 'Confirmation request',
            'question' => "Do you confirm the execution of the following actions ?",
        ],
        'alert'   => [
            "title" => [
                "success" => "Success",
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
            "status" => "Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>",
        ],
    ],
];