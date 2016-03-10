<?php

return [

    'settings' => [
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

    'driver' => 'gd'

];
