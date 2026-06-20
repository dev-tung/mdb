<?php

require_once PATH_SHOP . 'controller/ProductController.php';
require_once PATH_SHOP . 'controller/ExportController.php';
require_once PATH_SHOP . 'controller/ImportController.php';
require_once PATH_SHOP . 'controller/SupplierController.php';

return [

    'api/product/list' => [
        'controller' => ProductController::class,
        'action'     => 'list',
    ],

    'api/export/list' => [
        'controller' => ExportController::class,
        'action'     => 'list',
    ],

    'api/export/product' => [
        'controller' => ExportController::class,
        'action'     => 'product',
    ],

    'api/export/show' => [
        'controller' => ExportController::class,
        'action'     => 'show',
    ],

    'api/export/create' => [
        'controller' => ExportController::class,
        'action'     => 'create',
    ],

    'api/export/update' => [
        'controller' => ExportController::class,
        'action'     => 'update',
    ],

    'api/export/delete' => [
        'controller' => ExportController::class,
        'action'     => 'delete',
    ],

    'api/export/status' => [
        'controller' => ExportController::class,
        'action'     => 'status',
    ],

    'api/export/payment' => [
        'controller' => ExportController::class,
        'action'     => 'payment',
    ],


    // IMPORT
    'api/import/list' => [
        'controller' => ImportController::class,
        'action'     => 'list',
    ],

    'api/import/product' => [
        'controller' => ImportController::class,
        'action'     => 'product',
    ],

    'api/import/show' => [
        'controller' => ImportController::class,
        'action'     => 'show',
    ],

    'api/import/create' => [
        'controller' => ImportController::class,
        'action'     => 'create',
    ],

    'api/import/update' => [
        'controller' => ImportController::class,
        'action'     => 'update',
    ],

    'api/import/delete' => [
        'controller' => ImportController::class,
        'action'     => 'delete',
    ],

    'api/import/status' => [
        'controller' => ImportController::class,
        'action'     => 'status',
    ],

    'api/import/payment' => [
        'controller' => ImportController::class,
        'action'     => 'payment',
    ],

    // SUPPLIER
    'api/supplier/list' => [
        'controller' => SupplierController::class,
        'action'     => 'list',
    ],
];