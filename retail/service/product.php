<?php

require_once ROOT_PATH . 'helper.php';

function product_data(): array
{
    return [
        'racquet' => [
            ['name'=>'Yonex Astrox 100ZZ','brand'=>'yonex','price'=>5990000],
            ['name'=>'Yonex Astrox 99 Pro','brand'=>'yonex','price'=>5200000],
            ['name'=>'Yonex Nanoflare 1000Z','brand'=>'yonex','price'=>5490000],
            ['name'=>'Victor Thruster F','brand'=>'victor','price'=>4200000],
            ['name'=>'Victor Auraspeed 90K','brand'=>'victor','price'=>3900000],
        ],
        'shoes' => [
            ['name'=>'Yonex SHB 65Z3','brand'=>'yonex','price'=>2990000],
            ['name'=>'Victor P9200','brand'=>'victor','price'=>3200000],
            ['name'=>'Mizuno Wave Fang','brand'=>'mizuno','price'=>2800000],
        ],
        'bag' => [
            ['name'=>'Yonex Pro Bag','brand'=>'yonex','price'=>1490000],
            ['name'=>'Victor BR9209','brand'=>'victor','price'=>1390000],
            ['name'=>'Lining ABJT','brand'=>'lining','price'=>1200000],
        ],
        'accessory' => [
            ['name'=>'Quấn cán Yonex','brand'=>'yonex','price'=>50000],
            ['name'=>'BG80 String','brand'=>'yonex','price'=>120000],
            ['name'=>'Victor Grip Powder','brand'=>'victor','price'=>90000],
        ]
    ];
}

function product_context(): array
{
    return [
        'type'   => get_query('type', 'all'),
        'brands' => get_array('brand'),
        'price'  => get_query('price'),
        'page'   => max(1, (int)get_query('page', 1)),
        'perPage'=> 6
    ];
}

function product_filter(array $data, array $ctx): array
{
    $products = ($ctx['type'] === 'all')
        ? array_merge_flat($data)
        : ($data[$ctx['type']] ?? []);

    if ($ctx['brands']) {
        $products = array_filter($products, fn($p) =>
            in_array($p['brand'], $ctx['brands'])
        );
    }

    $products = array_filter($products, function ($p) use ($ctx) {
        return match ($ctx['price']) {
            'lt1' => $p['price'] < 1_000_000,
            '1-3' => $p['price'] <= 3_000_000,
            '3-5' => $p['price'] <= 5_000_000,
            'gt5' => $p['price'] > 5_000_000,
            default => true
        };
    });

    return array_values($products);
}

function product_service(): array
{
    $data = product_data();
    $ctx  = product_context();

    $filtered = product_filter($data, $ctx);
    $paged    = array_paginate($filtered, $ctx['page'], $ctx['perPage']);

    return [
        'products'   => $paged['data'],
        'page'       => $paged['page'],
        'totalPages' => $paged['totalPages'],
        'filters'    => $ctx
    ];
}

function product_build_query(array $extra = []): string
{
    return build_query($extra);
}