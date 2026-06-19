<?php
require_once PATH_ROOT . 'endpoint/BaseController.php';
require_once PATH_ROOT . 'endpoint/ProductController.php';
require_once PATH_ROOT . 'endpoint/CustomerController.php';
require_once PATH_ROOT . 'endpoint/ExportController.php';

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