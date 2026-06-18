<?php

require_once PATH_RETAIL . 'service/base.php';
require_once PATH_RETAIL . 'repository/product.php';

/* =========================
   SERVICE
========================= */

function product_service(): array
{
    $products   = get_products();
    $brands     = get_brands();
    $categories = get_categories();
    $ctx        = product_context();

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

/* =========================
   CONTEXT
========================= */

function product_context(): array
{
    return [
        'keyword'  => trim((string) get_query('keyword', '')),
        'category' => (int) get_query('category', 0),
        'brands'   => array_map('intval', get_array('brand') ?? []),
        'price'    => (string) get_query('price', ''),
        'page'     => max(1, (int) get_query('page', 1)),
        'perPage'  => 20,
    ];
}

/* =========================
   FILTER
========================= */

function product_filter(array $products, array $ctx): array
{
    $keyword  = mb_strtolower($ctx['keyword'] ?? '');
    $category = (int) ($ctx['category'] ?? 0);
    $brands   = $ctx['brands'] ?? [];
    $price    = $ctx['price'] ?? '';

    return array_values(array_filter($products, function ($p) use ($keyword, $category, $brands, $price) {

        // keyword
        if ($keyword !== '') {
            if (!str_contains(mb_strtolower($p['name'] ?? ''), $keyword)) {
                return false;
            }
        }

        // category
        if ($category > 0 && (int)($p['category_id'] ?? 0) !== $category) {
            return false;
        }

        // brands
        if (!empty($brands) && !in_array((int)($p['brand_id'] ?? 0), $brands, true)) {
            return false;
        }

        // price
        $priceValue = (float) ($p['price'] ?? 0);

        return match ($price) {
            'lt1' => $priceValue < 1_000_000,
            '1-3' => $priceValue >= 1_000_000 && $priceValue <= 3_000_000,
            '3-5' => $priceValue > 3_000_000 && $priceValue <= 5_000_000,
            'gt5' => $priceValue > 5_000_000,
            default => true,
        };
    }));
}

/* =========================
   QUERY
========================= */

function product_build_query(array $extra = []): string
{
    return build_query($extra);
}

/* =========================
   FORMAT HELPERS
========================= */

function product_price($price): string
{
    return number_format((float)$price, 0, ',', '.') . ' đ';
}

function product_status($status): string
{
    return $status ? 'Đang bán' : 'Ẩn';
}

function product_updated_at($p): string
{
    return $p['updated_at'] ?? $p['created_at'] ?? '';
}

function product_category_name($categoryId, $categories): string
{
    $categoryId = (int) $categoryId;

    foreach ($categories as $c) {
        if ((int)($c['id'] ?? 0) === $categoryId) {
            return $c['name'] ?? 'Chưa phân loại';
        }
    }

    return 'Chưa phân loại';
}

/* =========================
   IMAGE
========================= */

function product_image($thumbnail): string
{
    if (empty($thumbnail)) {
        return 'https://placehold.co/300x300?text=No+Image';
    }

    return str_starts_with($thumbnail, 'http')
        ? $thumbnail
        : URL_ROOT . '/retail/' . ltrim($thumbnail, '/');
}

function product_card_image($thumbnail): string
{
    return product_image($thumbnail);
}

/* =========================
   PAGINATION
========================= */

function product_index($page, $index): int
{
    $page  = max(1, (int)$page);
    $index = (int)$index;

    return (($page - 1) * 20) + $index + 1;
}

/* =========================
   CARD PRICE
========================= */

function product_card_price($price): string
{
    $price = (int) $price;

    return $price > 0
        ? number_format($price, 0, ',', '.') . '₫'
        : 'Liên hệ';
}