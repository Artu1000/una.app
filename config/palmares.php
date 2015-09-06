<?php

$categories = [
    1 => [
        'key' => 'france-ffsu-unss',
        'title' => 'Championnats de France FFSU / UNSS',
        'logo' => 'img/home/logo-ffsu.png',
        'class' => 'blue'
    ],
    2 => [
        'key' => 'france-ffa-senior',
        'title' => 'Championnats de France Senior',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'yellow'
    ],
    3 => [
        'key' => 'france-ffa-j18',
        'title' => 'Championnats de France J18',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'black'
    ],
    4 => [
        'key' => 'france-ffa-j16',
        'title' => 'Championnats de France J16',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'green'
    ],
    5 => [
        'key' => 'france-ffa-j14',
        'title' => 'Championnats de France J14',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'red'
    ],
    6 => [
        'key' => 'sprints-ffa-senior',
        'title' => 'Championnats de France Sprint Senior',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'blue'
    ],
    7 => [
        'key' => 'france-btx-courts',
        'title' => 'Championnats de France Senior bateaux courts',
        'logo' => 'img/home/logo-ffa.png',
        'class' => 'yellow'
    ],
    8 => [
        'key' => 'regates',
        'title' => 'Régates',
        'logo' => 'img/palmares/logo-una-small-black.png',
        'class' => 'black'
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