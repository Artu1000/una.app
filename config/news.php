<?php

$categories = [
    1 => 'club',
    2 => 'sport',
];

$categories_keys = [];
foreach ($categories as $id => $cat) {
    $categories_keys[$cat] = $id;
}

return [
    'category'     => $categories,
    'category_key' => $categories_keys,
];