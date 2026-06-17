<?php

function get_product_by_slug(string $slug): ?array
{
    $jsonFile = PATH_SHOP . 'json/yonex_product_detail_page_1.json';

    if (!file_exists($jsonFile)) {
        return null;
    }

    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        return null;
    }

    foreach ($data as $item) {

        if (($item['slug'] ?? '') !== $slug) {
            continue;
        }

        return [
            "name"        => $item['name'] ?? '',
            "slug"        => $item['slug'] ?? '',
            "url"         => $item['url'] ?? '',
            "category"    => $item['category'] ?? '',
            "title"       => $item['title'] ?? ($item['name'] ?? ''),
            "description" => $item['description'] ?? null,

            // images
            "images"       => $item['images'] ?? [],
            "local_images" => $item['local_images'] ?? [],

            // specs luôn là array
            "specs"        => is_array($item['specs'] ?? null) ? $item['specs'] : [],

            "detail_url"   => $item['detail_url'] ?? ''
        ];
    }

    return null;
}


/**
 * Get product list grouped by category
 */
function get_products(): array
{
    $jsonFile = PATH_SHOP . 'json/yonex_product.json';

    if (!file_exists($jsonFile)) {
        return [];
    }

    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        return [];
    }

    $result = [];

    foreach ($data as $item) {

        $category = $item['category'] ?? 'unknown';

        $result[$category][] = [
            "name"  => $item['name'] ?? '',
            "slug"  => $item['slug'] ?? '',
            "url"   => $item['url'] ?? '',
            "image" => $item['image'] ?? '',

            // fix: không ghép path sai nữa
            "local_image" => $item['image_file'] ?? null
        ];
    }

    return $result;
}