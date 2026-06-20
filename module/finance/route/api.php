<?php

return [

    /* =========================
       ACCOUNT
    ========================= */

    'api/finance/account/list' => [
        'controller' => AccountController::class,
        'action'     => 'list',
    ],

    'api/finance/account/create' => [
        'controller' => AccountController::class,
        'action'     => 'create',
    ],

    'api/finance/account/update' => [
        'controller' => AccountController::class,
        'action'     => 'update',
    ],

    'api/finance/account/delete' => [
        'controller' => AccountController::class,
        'action'     => 'delete',
    ],

    'api/finance/account/detail' => [
        'controller' => AccountController::class,
        'action'     => 'detail',
    ],

    /* =========================
       CATEGORY
    ========================= */

    'api/finance/category/list' => [
        'controller' => CategoryController::class,
        'action'     => 'list',
    ],

    'api/finance/category/create' => [
        'controller' => CategoryController::class,
        'action'     => 'create',
    ],

    'api/finance/category/update' => [
        'controller' => CategoryController::class,
        'action'     => 'update',
    ],

    'api/finance/category/delete' => [
        'controller' => CategoryController::class,
        'action'     => 'delete',
    ],

    /* =========================
       TRANSACTION
    ========================= */

    'api/finance/transaction/list' => [
        'controller' => TransactionController::class,
        'action'     => 'list',
    ],

    'api/finance/transaction/create' => [
        'controller' => TransactionController::class,
        'action'     => 'create',
    ],

    'api/finance/transaction/update' => [
        'controller' => TransactionController::class,
        'action'     => 'update',
    ],

    'api/finance/transaction/delete' => [
        'controller' => TransactionController::class,
        'action'     => 'delete',
    ],

    'api/finance/transaction/detail' => [
        'controller' => TransactionController::class,
        'action'     => 'detail',
    ],

    /* =========================
       DEBT
    ========================= */

    'api/finance/debt/list' => [
        'controller' => DebtController::class,
        'action'     => 'list',
    ],

    'api/finance/debt/create' => [
        'controller' => DebtController::class,
        'action'     => 'create',
    ],

    'api/finance/debt/update' => [
        'controller' => DebtController::class,
        'action'     => 'update',
    ],

    'api/finance/debt/delete' => [
        'controller' => DebtController::class,
        'action'     => 'delete',
    ],

    /* =========================
       REPORT
    ========================= */

    'api/finance/report/revenue' => [
        'controller' => ReportController::class,
        'action'     => 'revenue',
    ],

    'api/finance/report/expense' => [
        'controller' => ReportController::class,
        'action'     => 'expense',
    ],

    'api/finance/report/debt' => [
        'controller' => ReportController::class,
        'action'     => 'debt',
    ],

];