<?php
require_once PATH_ROOT . 'api/BaseController.php';
require_once PATH_ROOT . 'api/ProductController.php';
require_once PATH_ROOT . 'api/CustomerController.php';
require_once PATH_ROOT . 'api/InventoryController.php';

return [

    'api/product/list' => [
        'controller' => ProductController::class,
        'action'     => 'list',
    ],

    'api/inventory/product' => [
        'controller' => InventoryController::class,
        'action'     => 'product',
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