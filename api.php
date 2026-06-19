<?php
require_once PATH_RETAIL . 'api/BaseController.php';
require_once PATH_RETAIL . 'api/ProductController.php';

return [

    'api/product' => [
        'controller' => ProductController::class,
        'action'     => 'index',
    ],

    'api/product/{slug}' => [
        'controller' => ProductController::class,
        'action'     => 'show',
        'params'     => ['slug'],
    ],

];