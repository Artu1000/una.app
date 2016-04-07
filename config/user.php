<?php

// boards
$boards = [
    1 => 'student_leading_board',
    2 => 'leading_board',
    3 => 'executive_committee',
];
$boards_keys = [];
foreach ($boards as $id => $board) {
    $boards_keys[$board] = $id;
}

// statuses
$statuses = [
    1  => 'president',
    2  => 'student_president',
    3  => 'vice_president',
    4  => 'student_vice_president',
    5  => 'secretary_general',
    6  => 'student_secretary',
    7  => 'treasurer',
    8  => 'student_treasurer',
    9  => 'sportive_commission',
    10 => 'communication_commission',
    11 => 'equipment_commission',
    12 => 'leisure_commission',
    13 => 'employee',
    14 => 'user',
];
$statuses_keys = [];
foreach ($statuses as $id => $status) {
    $statuses_keys[$status] = $id;
}

// genders
$genders = [
    1 => 'male',
    2 => 'female',
];
$genders_keys = [];
foreach ($genders as $id => $gender) {
    $genders_keys[$gender] = $id;
}

return [
    'board'      => $boards,
    'board_key'  => $boards_keys,
    'status'     => $statuses,
    'status_key' => $statuses_keys,
    'gender'     => $genders,
    'gender_key' => $genders_keys,
];