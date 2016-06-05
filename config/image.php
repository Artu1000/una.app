<?php

$image_prefix = 'universite-nantes-aviron-una-';

return [

    "prefix"       => $image_prefix,

    // images configurations
    "settings"     => [
        "logo"         => [
            "name"      => [
                "dark"  => $image_prefix . "logo-dark",
                'light' => $image_prefix . "logo-light",
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
            "name"  => $image_prefix . "news-background-image",
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
            "name"  => $image_prefix . "schedules-background-image",
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
            "name"  => $image_prefix . "registration-background-image",
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
    "photos"       => [
        "background_image" => [
            "name"  => $image_prefix . "photos-background-image",
            "sizes" => [
                "admin" => [40, 40],
                "767"   => [767, 431],
                "991"   => [991, 557],
                "1199"  => [1199, 674],
                "1919"  => [1919, 1079],
                "2560"  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/photos'),
        "public_path"      => "img/photos",
    ],
    "videos"       => [
        "background_image" => [
            "name"  => $image_prefix . "videos-background-image",
            "sizes" => [
                "admin" => [40, 40],
                "767"   => [767, 431],
                "991"   => [991, 557],
                "1199"  => [1199, 674],
                "1919"  => [1919, 1079],
                "2560"  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/videos'),
        "public_path"      => "img/videos",
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
