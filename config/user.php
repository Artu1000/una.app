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
    2  => 'student-president',
    4  => 'vice-president',
    5  => 'secretary-general',
    3  => 'student-secretary',
    6  => 'treasurer',
    7  => 'student-treasurer',
    8  => 'sportive-commission',
    9  => 'communication-commission',
    10 => 'equipment-commission',
    11 => 'leisure-commission',
    12 => 'employee',
    13 => 'user',
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