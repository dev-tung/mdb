<?php

return [

    // Public
    ''       => ['path' => 'home.php'],
    'home'   => ['path' => 'home.php'],
    'search' => ['path' => 'search.php'],

    // Product
    'product'        => ['path' => 'retail/product.php'],
    'product/{slug}' => [
        'path'   => 'retail/product-detail.php',
        'params' => ['slug'],
    ],

    // Admin
    'admin/product' => [
        'path' => 'retail/admin/product/list.php',
    ],

    // Crawler
    'crawler/yonex-product-detail' => [
        'path' => 'retail/crawler/yonex-product-detail.php',
    ],

    // Import
    'import/yonex-product' => [
        'path' => 'retail/import/yonex-product.php',
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