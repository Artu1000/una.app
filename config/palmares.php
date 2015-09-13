<?php

$categories = [
    1 => [
        'key' => 'france-ffsu-unss',
        'title' => 'Championnats de France FFSU / UNSS',
        'logo' => 'img/home/logo-ffsu.png'
    ],
    2 => [
        'key' => 'france-ffa-senior',
        'title' => 'Championnats de France Senior',
        'logo' => 'img/home/logo-ffa.png'
    ],
    3 => [
        'key' => 'france-ffa-j18',
        'title' => 'Championnats de France J18',
        'logo' => 'img/home/logo-ffa.png'
    ],
    4 => [
        'key' => 'france-ffa-j16',
        'title' => 'Championnats de France J16',
        'logo' => 'img/home/logo-ffa.png'
    ],
    5 => [
        'key' => 'france-ffa-j14',
        'title' => 'Championnats de France J14',
        'logo' => 'img/home/logo-ffa.png'
    ],
    6 => [
        'key' => 'sprints-ffa-senior',
        'title' => 'Championnats de France Sprint Senior',
        'logo' => 'img/home/logo-ffa.png'
    ],
    7 => [
        'key' => 'france-btx-courts',
        'title' => 'Championnats de France Senior bateaux courts',
        'logo' => 'img/home/logo-ffa.png'
    ],
    8 => [
        'key' => 'regates',
        'title' => 'Régates',
        'logo' => 'img/palmares/logo-una-small-black.png'
    ]
];

$categories_keys = [];
foreach ($categories as $id => $cat) {
    $categories_keys[$cat['key']] = $id;
}

return [
    'categories' => $categories,
    'categories_keys' => $categories_keys
];