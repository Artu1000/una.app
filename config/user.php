<?php

// boards
$boards = [
    1 => [
        'key'   => 'student-leading-board',
        'title' => 'Bureau Étudiant',
    ],
    2 => [
        'key'   => 'leading-board',
        'title' => 'Bureau',
    ],
    3 => [
        'key'   => 'executive-committee',
        'title' => 'Comité Directeur',
    ],
];
$boards_keys = [];
foreach ($boards as $id => $board) {
    $boards_keys[$board['key']] = $id;
}

// statuses
$statuses = [
    1  => [
        'key'   => 'president',
        'title' => 'Président',
    ],
    2  => [
        'key'   => 'student-president',
        'title' => 'Président Étudiant',
    ],
    3  => [
        'key'   => 'student-secretary',
        'title' => 'Secrétaire',
    ],
    4  => [
        'key'   => 'vice-president',
        'title' => 'Vice-Président',
    ],
    5  => [
        'key'   => 'secretary-general',
        'title' => 'Secrétaire Général',
    ],
    6  => [
        'key'   => 'treasurer',
        'title' => 'Trésorier',
    ],
    7  => [
        'key'   => 'sportive-commission',
        'title' => 'Commission Sportive',
    ],
    8  => [
        'key'   => 'communication-commission',
        'title' => 'Commission Communication',
    ],
    9  => [
        'key'   => 'equipment-commission',
        'title' => 'Commission Matériel',
    ],
    10  => [
        'key'   => 'leisure-commission',
        'title' => 'Commission Loisirs',
    ],
    11 => [
        'key'   => 'employee',
        'title' => 'Manager',
    ],
    12 => [
        'key'   => 'association-member',
        'title' => 'Sociétaire',
    ],
];
$statuses_keys = [];
foreach ($statuses as $id => $status) {
    $statuses_keys[$status['key']] = $id;
}

// gender
$genders = [
    1 => [
        'key'   => 'male',
        'title' => 'Homme',
    ],
    2 => [
        'key'   => 'female',
        'title' => 'Femme',
    ],
];
$genders_keys = [];
foreach ($genders as $id => $gender) {
    $genders_keys[$gender['key']] = [
        'id'    => $id,
        'title' => $gender['title'],
    ];
}

return [
    'status'     => $statuses,
    'status_key' => $statuses_keys,
    'board'      => $boards,
    'board_key'  => $boards_keys,
    'gender'     => $genders,
    'gender_key' => $genders_keys,
];