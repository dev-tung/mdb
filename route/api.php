<?php
require_once PATH_ROOT . 'controller/BaseController.php';
require_once PATH_ROOT . 'controller/ProductController.php';
require_once PATH_ROOT . 'controller/CustomerController.php';
require_once PATH_ROOT . 'controller/ExportController.php';

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