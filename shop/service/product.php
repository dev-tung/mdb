<?php

require_once PATH_SHOP . 'service/base.php';
require_once PATH_SHOP . 'repository/product.php';

function product_service(): array
{
    $data = get_products();
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

function product_context(): array
{
    return [
        'type'   => get_query('type', 'all'),
        'brands' => get_array('brand'),
        'price'  => get_query('price'),
        'page'   => max(1, (int)get_query('page', 1)),
        'perPage'=> 30
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

function product_build_query(array $extra = []): string
{
    return build_query($extra);
}