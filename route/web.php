<?php

return [

    // Public
    ''       => ['path' => 'home.php'],
    'home'   => ['path' => 'home.php'],
    'search' => ['path' => 'search.php'],

    // Product
    'product'        => ['path' => 'module/shop/product.php'],
    'product/{slug}' => [
        'path'   => '/shop/product-detail.php',
        'params' => ['slug'],
    ],

    // Admin
    'admin/product' => [
        'path' => 'module/shop/admin/product/list.php',
    ],

    'admin/export/create' => [
        'path' => 'module/shop/admin/export/create.php',
    ],

    'admin/import/create' => [
        'path' => 'module/shop/admin/import/create.php',
    ],

    'admin/customer' => [
        'path' => 'module/customer/admin/list.php',
    ],

    'admin/supplier' => [
        'path' => 'module/shop/admin/supplier/list.php',
    ],

    // Crawler
    'crawler/yonex-product-detail' => [
        'path' => 'module/shop/crawler/yonex-product-detail.php',
    ],

    // Import
    'import/yonex-product' => [
        'path' => 'module/shop/import/yonex-product.php',
    ],

    // Stringing
    'string' => [
        'path' => 'module/stringing/table.php',
    ],

    // Human
    'affilate' => [
        'path' => 'module/human/affilate.php',
    ],

    'recruitment' => [
        'path' => 'module/human/recruitment.php',
    ],

];