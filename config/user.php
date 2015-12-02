<?php

// boards
$boards = [
    1 => [
        'key' => 'leading-board',
        'title' => 'Bureau'
    ],
    2 => [
        'key' => 'executive-committee',
        'title' => 'Comité Directeur'
    ]
];

$boards_keys = [];
foreach ($boards as $id => $board) {
    $boards_keys[$board['key']] = $id;
}

// statuses
$statuses = [
    1 => [
        'key' => 'president',
        'title' => 'Président'
    ],
    2 => [
        'key' => 'vice-president',
        'title' => 'Vice-Président'
    ],
    3 => [
        'key' => 'student-vice-president',
        'title' => 'Vice-Président Étudiant'
    ],
    4 => [
        'key' => 'secretary-general',
        'title' => 'Secrétaire Général'
    ],
    5 => [
        'key' => 'treasurer',
        'title' => 'Trésorier'
    ],
    6 => [
        'key' => 'sportive-commission',
        'title' => 'Commission Sportive'
    ],
    7 => [
        'key' => 'communication-commission',
        'title' => 'Commission Communication'
    ],
    8 => [
        'key' => 'equipment-commission',
        'title' => 'Commission Matériel'
    ],
    9 => [
        'key' => 'leisure-commission',
        'title' => 'Commission Loisirs'
    ],
    10 => [
        'key' => 'employee',
        'title' => 'Manager'
    ],
    11 => [
        'key' => 'association-member',
        'title' => 'Sociétaire'
    ]
];
$statuses_keys = [];
foreach ($statuses as $id => $status) {
    $statuses_keys[$status['key']] = $id;
}

// gender
$genders = [
    1 => [
        'key' => 'male',
        'title' => 'Homme'
    ],
    2 => [
        'key' => 'female',
        'title' => 'Femme'
    ]
];
$genders_keys = [];
foreach ($genders as $id => $gender) {
    $genders_keys[$gender['key']] = [
        'id' => $id,
        'title' => $gender['title']
    ];
}

// hierarchy
//$hierarchy = [
//    1 => 'admin',
//    2 => 'mod',
//    1 => 'admin',
//    1 => 'admin',
//];

return [
    'status' => $statuses,
    'status_key' => $statuses_keys,
    'board' => $boards,
    'board_key' => $boards_keys,
    'gender' => $genders,
    'gender_key' => $genders_keys
];