<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PURCHASE WORKFLOW (quy trình nhập hàng)
    |--------------------------------------------------------------------------
    */
    'purchase_status' => [
        'draft' => [
            'label' => 'Nháp',
            'color' => 'gray',
        ],
        'confirmed' => [
            'label' => 'Đã xác nhận',
            'color' => 'blue',
        ],
        'received' => [
            'label' => 'Đã nhận hàng',
            'color' => 'green',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS (thanh toán)
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'unpaid' => [
            'label' => 'Chưa thanh toán',
            'color' => 'red',
        ],
        'partial' => [
            'label' => 'Thanh toán một phần',
            'color' => 'orange',
        ],
        'paid' => [
            'label' => 'Đã thanh toán',
            'color' => 'green',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS (bán hàng)
    |--------------------------------------------------------------------------
    */
    'order_status' => [
        'pending' => [
            'label' => 'Chờ xử lý',
            'color' => 'orange',
        ],
        'paid' => [
            'label' => 'Đã thanh toán',
            'color' => 'green',
        ],
    ],

];