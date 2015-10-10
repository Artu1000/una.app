<?php

// article categories
$article_categories = [
    1 => [
        'key' => 'textile',
        'title' => 'Textile'
    ],
    2 => [
        'key' => 'goodies',
        'title' => 'Goodies'
    ]
];
// keys
$article_categories_keys = [];
foreach ($article_categories as $id => $category) {
    $article_categories_keys[$category['key']] = $id;
}

// order types
$availability_types = [
    1 => [
        'key' => 'in-stock',
        'title' => 'En stock'
    ],
    2 => [
        'key' => 'on-order',
        'title' => 'Sur commande'
    ],
    3 => [
        'key' => 'depleted',
        'title' => 'Épuisé'
    ]
];
// keys
$availability_types_keys = [];
foreach ($availability_types as $id => $type) {
    $availability_types_keys[$type['key']] = $id;
}

return [
    'article_category' => $article_categories,
    'article_category_key' => $article_categories_keys,
    'availability_type' => $availability_types,
    'availability_type_key' => $availability_types_keys
];