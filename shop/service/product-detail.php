<?php

require_once PATH_SHOP . 'service/base.php';
require_once PATH_SHOP . 'repository/product.php';

function product_detail_service(): array
{
    $slug = product_detail_context();

    // ❌ không có slug thì dừng luôn
    if (!$slug) {
        return [
            'found' => false,
            'product' => null,
            'related_products' => []
        ];
    }

    $product = get_product_by_slug($slug);

    // ❌ không tìm thấy sản phẩm
    if (!$product) {
        return [
            'found' => false,
            'product' => null,
            'related_products' => []
        ];
    }

    return [
        'found' => true,
        'product' => product_detail_format($product),
        'related_products' => get_related_products($product)
    ];
}


/**
 * Lấy slug từ URL query
 */
function product_detail_context(): string
{
    return trim(get_query('slug', ''));
}


/**
 * Format data cho VIEW (chuẩn hóa output)
 */
function product_detail_format(array $p): array
{
    return [
        'slug' => $p['slug'] ?? '',
        'name' => $p['name'] ?? '',

        // image gallery (array)
        'images' => $p['images'] ?? [],

        // local images (array)
        'local_images' => $p['local_images'] ?? [],

        'price' => $p['price'] ?? 0,
        'brand' => $p['brand'] ?? '',
        'sku' => $p['sku'] ?? '',

        'category' => $p['category'] ?? '',
        'category_name' => $p['category_name'] ?? '',

        'description' => $p['description'] ?? '',

        // FIX: đồng bộ repo (specs)
        'specifications' => $p['specs'] ?? []
    ];
}


/**
 * Lấy sản phẩm liên quan (cùng category, trừ chính nó)
 */
function get_related_products(array $product): array
{
    $all = get_products();

    $related = array_filter($all, function ($p) use ($product) {

        return ($p['slug'] ?? '') !== ($product['slug'] ?? '')
            && ($p['category'] ?? '') === ($product['category'] ?? '');

    });

    return array_slice(array_values($related), 0, 4);
}