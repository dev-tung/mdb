<?php

require_once PATH_SHOP . 'service/base.php';
require_once PATH_SHOP . 'repository/product.php';

function product_detail_service(): array
{
    $slug = trim(get_query('slug', ''));

    $product = $slug ? get_product_by_slug($slug) : null;

    if (!$product) {
        return [
            'found' => false,

            // view không cần if nữa → vẫn render được safe data
            'product' => [
                'name' => 'Product not found',
                'category' => '',
                'description' => '',
                'price' => 0,
                'images' => ['https://placehold.co/600x600'],
                'specifications' => []
            ],

            'related_products' => []
        ];
    }

    return [
        'found' => true,
        'product' => format_product_for_view($product),
        'related_products' => get_related_products($product)
    ];
}


/**
 * VIEW-READY DATA (100% clean, no logic in view)
 */
function format_product_for_view(array $p): array
{
    $images = array_map(function ($img) {

        if (empty($img)) {
            return 'https://placehold.co/600x600';
        }

        if (str_starts_with($img, 'http')) {
            return $img;
        }

        return URL_ROOT . '/shop/' . ltrim($img, '/');

    }, $p['images'] ?? []);

    return [
        'name' => $p['name'] ?? '',
        'category' => $p['category'] ?? '',
        'description' => $p['description'] ?? '',
        'price' => $p['price'] ?? 0,

        // already safe
        'images' => $images,

        // already safe fallback
        'main_image' => $images[0] ?? 'https://placehold.co/600x600',

        'specifications' => $p['specs'] ?? []
    ];
}