<?php

require_once PATH_SHOP . 'service/base.php';
require_once PATH_SHOP . 'repository/product.php';

function product_detail_service(): array
{
    $slug = trim(get_query('slug', ''));

    if (!$slug) {
        return not_found_response();
    }

    $product = get_product_by_slug($slug);

    if (!$product) {
        return not_found_response();
    }

    $productId = (int)($product['id'] ?? 0);

    $specs  = get_product_specs($productId);
    $images = get_product_images($productId);

    return [
        'found' => true,
        'product' => format_product_for_view($product, $specs, $images),
        'related_products' => get_related_products($product)
    ];
}

/**
 * NOT FOUND SAFE RESPONSE
 */
function not_found_response(): array
{
    return [
        'found' => false,
        'product' => [
            'name' => 'Product not found',
            'category' => '',
            'description' => '',
            'price' => 0,
            'images' => ['https://placehold.co/600x600'],
            'main_image' => 'https://placehold.co/600x600',
            'specifications' => []
        ],
        'related_products' => []
    ];
}

/**
 * VIEW-READY FORMAT
 */
function format_product_for_view(array $p, array $specs, array $images): array
{
    $images = array_map(function ($img) {

        if (empty($img)) {
            return 'https://placehold.co/600x600';
        }

        if (str_starts_with($img, 'http')) {
            return $img;
        }

        return URL_ROOT . '/shop/' . ltrim($img, '/');

    }, $images);

    return [
        'name' => $p['name'] ?? '',
        'category' => $p['category'] ?? '',
        'description' => $p['description'] ?? '',
        'price' => $p['price'] ?? 0,

        'images' => $images,
        'main_image' => $images[0] ?? 'https://placehold.co/600x600',

        'specifications' => $specs
    ];
}