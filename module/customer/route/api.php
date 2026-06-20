<?php
require_once PATH_CUSTOMER . 'controller/CustomerController.php';

return [
    'api/customer/list' => [
        'controller' => CustomerController::class,
        'action'     => 'list',
    ]
];