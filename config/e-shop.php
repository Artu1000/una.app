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
$order_types = [
    1 => [
        'key' => 'in-stock',
        'title' => 'En stock'
    ],
    2 => [
        'key' => 'on-order',
        'title' => 'Sur commande'
    ]
];
// keys
$order_types_keys = [];
foreach ($order_types as $id => $type) {
    $order_types_keys[$type['key']] = $id;
}

return [
    'article_category' => $article_categories,
    'article_category_key' => $article_categories_keys,
    'order_type' => $order_types,
    'order_type_key' => $order_types_keys
];