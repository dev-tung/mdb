<?php

function get_product_by_slug(string $slug): ?array
{
    return DB::row(
        "SELECT *
         FROM shop_product
         WHERE slug = ?
         LIMIT 1",
        [$slug]
    );
}

function get_products(): array
{
    return DB::all(
        "SELECT *
         FROM shop_product
         WHERE status = 1
         ORDER BY id DESC"
    );
}

function get_brands(): array
{
    return DB::all(
        "SELECT id, name, slug
         FROM shop_brand
         ORDER BY name"
    );
}

function get_categories(): array
{
    return DB::all(
        "SELECT id, name, slug, thumbnail
         FROM shop_category
         ORDER BY name"
    );
}

function get_related_products(array $product, int $limit = 4): array
{
    if (empty($product['category_id'])) {
        return [];
    }

    return DB::all(
        "SELECT *
         FROM shop_product
         WHERE category_id = ?
           AND slug != ?
           AND status = 1
         ORDER BY id DESC
         LIMIT {$limit}",
        [
            $product['category_id'],
            $product['slug']
        ]
    );
}

function get_featured_products(int $limit = 8): array
{
    return DB::all(
        "SELECT *
         FROM shop_product
         WHERE status = 1
           AND category_id = 1
         ORDER BY id DESC
         LIMIT {$limit}"
    );
}

function search_products(string $keyword): array
{
    $keyword = trim(preg_replace('/\s+/', ' ', $keyword));

    if ($keyword === '') {
        return [];
    }

    $words = explode(' ', $keyword);

    $whereClauses = [];
    $params = [];

    foreach ($words as $word) {

        $whereClauses[] =
            "(p.name LIKE ?
            OR b.name LIKE ?
            OR c.name LIKE ?)";

        $search = "%{$word}%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereSql = implode(' AND ', $whereClauses);

    return DB::all(
        "SELECT
            p.*,
            b.name AS brand_name,
            c.name AS category_name
        FROM shop_product p
        LEFT JOIN shop_brand b
            ON b.id = p.brand_id
        LEFT JOIN shop_category c
            ON c.id = p.category_id
        WHERE p.status = 1
          AND {$whereSql}
        ORDER BY p.name ASC",
        $params
    );
}

function get_product_specs(int $productId): array
{
    $rows = DB::all(
        "SELECT spec_key, spec_value
         FROM shop_product_spec
         WHERE product_id = ?",
        [$productId]
    );

    $specs = [];

    foreach ($rows as $row) {
        $specs[$row['spec_key']] = $row['spec_value'];
    }

    return $specs;
}

function get_product_images(int $productId): array
{
    $rows = DB::all(
        "SELECT image
         FROM shop_product_image
         WHERE product_id = ?
         ORDER BY sort_order ASC",
        [$productId]
    );

    return array_column($rows, 'image');
}