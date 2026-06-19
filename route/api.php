<?php
require_once PATH_ROOT . 'controller/BaseController.php';
require_once PATH_ROOT . 'controller/ProductController.php';
require_once PATH_ROOT . 'controller/CustomerController.php';
require_once PATH_ROOT . 'controller/ExportController.php';
require_once PATH_ROOT . 'controller/ImportController.php';
require_once PATH_ROOT . 'controller/SupplierController.php';

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

    'api/import/product' => [
        'controller' => ImportController::class,
        'action'     => 'product',
    ],

    'api/import/create' => [
        'controller' => ImportController::class,
        'action'     => 'create',
    ],

    'api/customer/list' => [
        'controller' => CustomerController::class,
        'action'     => 'list',
    ],

    'api/supplier/list' => [
        'controller' => SupplierController::class,
        'action'     => 'list',
    ],



];