<?php
require_once PATH_ROOT . 'api/BaseController.php';
require_once PATH_ROOT . 'api/ProductController.php';
require_once PATH_ROOT . 'api/CustomerController.php';

return [

    'api/product/list' => [
        'controller' => ProductController::class,
        'action'     => 'list',
    ],

    'api/product/search' => [
        'controller' => ProductController::class,
        'action'     => 'search',
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