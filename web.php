<?php

return [

    // Public
    ''       => ['path' => 'home.php'],
    'home'   => ['path' => 'home.php'],
    'search' => ['path' => 'search.php'],

    // Product
    'product'        => ['path' => 'shop/product.php'],
    'product/{slug}' => [
        'path'   => 'shop/product-detail.php',
        'params' => ['slug'],
    ],

    // Admin
    'admin/product' => [
        'path' => 'shop/admin/product/list.php',
    ],

    // Crawler
    'crawler/yonex-product-detail' => [
        'path' => 'shop/crawler/yonex-product-detail.php',
    ],

    // Import
    'import/yonex-product' => [
        'path' => 'shop/import/yonex-product.php',
    ],

    // Stringing
    'string' => [
        'path' => 'stringing/table.php',
    ],

    // Human
    'affilate' => [
        'path' => 'human/affilate.php',
    ],

    'recruitment' => [
        'path' => 'human/recruitment.php',
    ],

];