<?php

return [

    // images configurations
    "settings"     => [
        "logo"         => [
            "name"      => [
                "dark"  => "universite-nantes-aviron-una-logo-dark",
                'light' => "universite-nantes-aviron-una-logo-light",
            ],
            "extension" => "png",
            "sizes"     => [
                "admin"  => [40, 40],
                "header" => [150, null],
                "large"  => [300, null],
            ],
        ],
        "storage_path" => storage_path('app/settings'),
        "public_path"  => "img/settings",
    ],
    "news"         => [
        "background_image" => [
            "name"  => "universite-nantes-aviron-una-news-background-image",
            "sizes" => [
                "admin" => [40, 40],
                "767"   => [767, 431],
                "991"   => [991, 557],
                "1199"  => [1199, 674],
                "1919"  => [1919, 1079],
                "2560"  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/news'),
        "public_path"      => "img/news",
    ],
    "schedules"    => [
        "background_image" => [
            "name"  => "universite-nantes-aviron-una-schedules-background-image",
            "sizes" => [
                "admin" => [40, 40],
                "767"   => [767, 431],
                "991"   => [991, 557],
                "1199"  => [1199, 674],
                "1919"  => [1919, 1079],
                "2560"  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/schedules'),
        "public_path"      => "img/schedules",
    ],
    "registration" => [
        "background_image" => [
            "name"  => "universite-nantes-aviron-una-registration-background-image",
            "sizes" => [
                "admin" => [40, 40],
                "767"   => [767, 431],
                "991"   => [991, 557],
                "1199"  => [1199, 674],
                "1919"  => [1919, 1079],
                "2560"  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/registration'),
        "public_path"      => "img/registration",
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */
    
    "driver" => "gd",

];
