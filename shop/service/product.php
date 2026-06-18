<?php

require_once PATH_SHOP . 'service/base.php';
require_once PATH_SHOP . 'repository/product.php';

function product_service(): array
{
    $products   = get_products();
    $brands     = get_brands();
    $categories = get_categories();

    $ctx = product_context();

    $products = product_filter($products, $ctx);

    $paged = array_paginate(
        $products,
        $ctx['page'],
        $ctx['perPage']
    );

    return [
        'products'   => $paged['data'],
        'brands'     => $brands,
        'categories' => $categories,
        'page'       => $paged['page'],
        'totalPages' => $paged['totalPages'],
        'filters'    => $ctx
    ];
}

function product_context(): array
{
    return [
        'category' => (int)get_query('category'),
        'brands'   => array_map('intval', get_array('brand')),
        'price'    => get_query('price'),
        'page'     => max(1, (int)get_query('page', 1)),
        'perPage'  => 20
    ];
}

function product_filter(array $products, array $ctx): array
{
    if ($ctx['category']) {
        $products = array_filter(
            $products,
            fn($p) => (int)$p['category_id'] === $ctx['category']
        );
    }

    if ($ctx['brands']) {
        $products = array_filter(
            $products,
            fn($p) => in_array(
                (int)($p['brand_id'] ?? 0),
                $ctx['brands']
            )
        );
    }

    $products = array_filter(
        $products,
        function ($p) use ($ctx) {

            $price = (float)($p['price'] ?? 0);

            return match ($ctx['price']) {

                'lt1' => $price < 1_000_000,

                '1-3' => $price >= 1_000_000
                    && $price <= 3_000_000,

                '3-5' => $price > 3_000_000
                    && $price <= 5_000_000,

                'gt5' => $price > 5_000_000,

                default => true
            };
        }
    );

    return array_values($products);
}

function product_build_query(array $extra = []): string
{
    return build_query($extra);
}

