<?php

/**
 * Load JSON
 */
function load_json(string $path): array
{
    if (!file_exists($path)) {
        die("File not found: {$path}");
    }

    $data = json_decode(file_get_contents($path), true);

    if (!is_array($data)) {
        die("Invalid JSON format");
    }

    return $data;
}

/**
 * Category Vietnamese Name
 */
function category_name_vi(string $slug, string $name = ''): string
{
    return match ($slug) {

        'racquets'          => 'Vợt cầu lông',
        'strings'           => 'Cước cầu lông',
        'stringing-machines'=> 'Máy đan vợt',
        'shuttlecocks'      => 'Cầu lông',
        'apparel'           => 'Quần áo cầu lông',
        'footwear'          => 'Giày cầu lông',
        'bags'              => 'Túi cầu lông',
        'accessories'       => 'Phụ kiện cầu lông',

        default => $name ?: ucfirst(str_replace('-', ' ', $slug))
    };
}

/**
 * Reset Data
 */
function reset_data(): void
{
    db_query("SET FOREIGN_KEY_CHECKS = 0");

    db_query("TRUNCATE TABLE shop_product");
    db_query("TRUNCATE TABLE shop_category");

    db_query("SET FOREIGN_KEY_CHECKS = 1");
}

/**
 * Import Categories
 */
function import_categories(array $data): void
{
    $ignore = [
        'racquetselector',
        'nanoflare'
    ];

    foreach ($data as $item) {

        $slug = trim($item['slug'] ?? '');

        if (!$slug || in_array($slug, $ignore)) {
            continue;
        }

        db_insert('shop_category', [
            'name'        => category_name_vi(
                $slug,
                $item['name'] ?? ''
            ),
            'slug'        => $slug,
            'description' => null
        ]);
    }
}

/**
 * Get Category ID
 */
function get_category_id(string $slug): int
{
    $row = db_one(
        "SELECT id
         FROM shop_category
         WHERE slug = ?",
        [$slug]
    );

    return (int)($row['id'] ?? 0);
}

/**
 * Get Yonex Brand ID
 */
function get_brand_id(): int
{
    $row = db_one(
        "SELECT id
         FROM shop_brand
         WHERE slug = ?",
        ['yonex']
    );

    if (!$row) {
        die('Brand yonex not found');
    }

    return (int)$row['id'];
}

/**
 * Import Products
 */
/**
 * Import Products
 */
function import_products(array $data): void
{
    $brandId = get_brand_id();

    $importedSlugs = [];

    foreach ($data as $item) {

        $name = trim($item['name'] ?? '');
        $slug = trim($item['slug'] ?? '');

        if (!$name || !$slug) {
            continue;
        }

        // trùng trong file JSON
        if (isset($importedSlugs[$slug])) {
            continue;
        }

        // trùng trong DB
        if (
            db_one(
                "SELECT id
                 FROM shop_product
                 WHERE slug = ?
                 LIMIT 1",
                [$slug]
            )
        ) {
            continue;
        }

        $importedSlugs[$slug] = true;

        $categoryId = get_category_id(
            $item['category'] ?? ''
        );

        db_insert('shop_product', [
            'category_id' => $categoryId,
            'brand_id'    => $brandId,
            'name'        => $name,
            'slug'        => $slug,
            'thumbnail'   => $item['image_file'] ?? null,
            'description' => null,
            'price'       => 0,
            'status'      => 1
        ]);
    }
}

/**
 * RUN
 */
$categoryFile = PATH_SHOP . 'json/yonex_category.json';
$productFile  = PATH_SHOP . 'json/yonex_product.json';

$categories = load_json($categoryFile);
$products   = load_json($productFile);

reset_data();

import_categories($categories);
import_products($products);

echo 'IMPORT DONE!';