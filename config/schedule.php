<?php

// days of week
$days_of_week = [
    1 => 'monday',
    2 => 'tuesday',
    3 => 'wednesday',
    4 => 'thursday',
    5 => 'friday',
    6 => 'saturday',
    7 => 'sunday',
];
// keys
$days_of_week_keys = [];
foreach ($days_of_week as $id => $day) {
    $days_of_week_keys[$day] = $id;
}


// public categories
$public_categories = [
    1 => 'all_publics',
    2 => 'suaps',
    3 => 'rowing_school',
    4 => 'competition',
];
// keys
$public_categories_keys = [];
foreach ($public_categories as $id => $cat) {
    $public_categories_keys[$cat] = $id;
}

return [
    'day_start'           => '08:00',
    'day_stop'            => '20:00',
    'day_of_week'         => $days_of_week,
    'day_of_week_key'     => $days_of_week_keys,
    'public_category'     => $public_categories,
    'public_category_key' => $public_categories_keys,
];