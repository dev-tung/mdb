<?php
require_once PATH_ROOT . 'api/BaseController.php';
require_once PATH_ROOT . 'api/ProductController.php';
require_once PATH_ROOT . 'api/CustomerController.php';
require_once PATH_ROOT . 'api/ExportController.php';

return [

    'api/product/list' => [
        'controller' => ProductController::class,
        'action'     => 'list',
    ],

    'api/export/product' => [
        'controller' => ExportController::class,
        'action'     => 'product',
    ],

    'api/export/create' => [
        'controller' => ExportController::class,
        'action'     => 'create',
    ],

    'api/customer/list' => [
        'controller' => CustomerController::class,
        'action'     => 'list',
    ],

    'api/customer/search' => [
        'controller' => CustomerController::class,
        'action'     => 'search',
    ],

];