<?php

// days of week
$days_of_week = [
    1 => [
        'key' => 'monday',
        'title' => 'Lundi'
    ],
    2 => [
        'key' => 'tuesday',
        'title' => 'Mardi'
    ],
    3 => [
        'key' => 'wednesday',
        'title' => 'Mercredi'
    ],
    4 => [
        'key' => 'thursday',
        'title' => 'Jeudi'
    ],
    5 => [
        'key' => 'friday',
        'title' => 'Vendredi'
    ],
    6 => [
        'key' => 'saturday',
        'title' => 'Samedi'
    ],
    7 => [
        'key' => 'sunday',
        'title' => 'Dimanche'
    ]
];
// keys
$days_of_week_keys = [];
foreach ($days_of_week as $id => $day) {
    $days_of_week_keys[$day['key']] = $id;
}


// public categories
$public_categories = [
    1 => [
        'key' => 'all-publics',
        'title' => 'Tous publics'
    ],
    2 => [
        'key' => 'suaps',
        'title' => 'SUAPS (étudiants)'
    ],
    3 => [
        'key' => 'rowing-school',
        'title' => 'École d\'Aviron (- de 18 ans)'
    ],
    4 => [
        'key' => 'competition',
        'title' => 'Compétition'
    ]
];
// keys
$public_categories_keys = [];
foreach ($public_categories as $id => $cat) {
    $public_categories_keys[$cat['key']] = $id;
}

return [
    'day_start' => '08:00',
    'day_stop' => '20:00',
    'day_of_week' => $days_of_week,
    'day_of_week_key' => $days_of_week_keys,
    'public_category' => $public_categories,
    'public_category_key' => $public_categories_keys
];