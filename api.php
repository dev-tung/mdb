<?php
require_once PATH_SHOP . 'api/BaseController.php';
require_once PATH_SHOP . 'api/ProductController.php';

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