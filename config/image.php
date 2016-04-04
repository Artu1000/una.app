<?php

return [

    // images configurations
    'settings'  => [
        'logo'         => [
            'name'      => [
                'dark'  => 'logo_dark',
                'light' => 'logo_light',
            ],
            'extension' => 'png',
            'sizes'     => [
                'admin'  => [40, 40],
                'header' => [150, null],
                'large'  => [300, null],
            ],
        ],
        'storage_path' => storage_path('app/settings'),
        'public_path'  => 'img/settings',
    ],
    "schedules" => [
        "background_image" => [
            "name"  => "schedules_background_image",
            'sizes' => [
                'admin' => [40, 40],
                '767'   => [767, 431],
                '991'   => [991, 557],
                '1199'  => [1199, 674],
                '1919'  => [1919, 1079],
                '2560'  => [2560, 1440],
            ],
        ],
        "storage_path"     => storage_path('app/schedules'),
        'public_path'      => 'img/schedules',
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
    
    'driver' => 'gd',

];
