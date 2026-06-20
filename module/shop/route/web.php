<?php

return [

    // Public
    ''       => ['path' => 'home.php'],
    'home'   => ['path' => 'home.php'],
    'search' => ['path' => 'search.php'],


    // Crawler
    'crawler/yonex-product-detail' => [
        'path' => 'module/shop/view/crawler/yonex-product-detail.php',
    ],

    // Import
    'import/yonex-product' => [
        'path' => 'module/shop/view/import/yonex-product.php',
    ],

    // Product
    'product' => [
        'path' => 'module/shop/view/product.php',
    ],

    'product/{slug}' => [
        'path'   => 'module/shop/view/product-detail.php',
        'params' => ['slug'],
    ],

    // Admin Product
    'admin/product' => [
        'path' => 'module/shop/view/admin/product/list.php',
    ],

    // Admin Export
    'admin/export' => [
        'path' => 'module/shop/view/admin/export/list.php',
    ],

    'admin/export/create' => [
        'path' => 'module/shop/view/admin/export/create.php',
    ],

    'admin/export/edit' => [
        'path' => 'module/shop/view/admin/export/edit.php',
    ],

    // Admin Import
    'admin/import' => [
        'path' => 'module/shop/view/admin/import/list.php',
    ],

    'admin/import/create' => [
        'path' => 'module/shop/view/admin/import/create.php',
    ],

    // Admin Supplier
    'admin/supplier' => [
        'path' => 'module/shop/view/admin/supplier/list.php',
    ],




];