<?php

function get_product_by_slug(string $slug): ?array
{
    return db_one(
        "SELECT *
         FROM shop_product
         WHERE slug = :slug
         LIMIT 1",
        ['slug' => $slug]
    );
}

function get_products(): array
{
    return db_all(
        "SELECT *
         FROM shop_product
         WHERE status = 1
         ORDER BY id DESC"
    );
}

function get_brands(): array
{
    return db_all(
        "SELECT id, name, slug
         FROM shop_brand
         ORDER BY name"
    );
}

function get_categories(): array
{
    return db_all(
        "SELECT id, name, slug
         FROM shop_category
         ORDER BY name"
    );
}

function get_related_products(array $product, int $limit = 4): array
{
    if (empty($product['category_id'])) {
        return [];
    }

    return db_all(
        "SELECT *
         FROM shop_product
         WHERE category_id = :category_id
           AND slug != :slug
           AND status = 1
         ORDER BY id DESC
         LIMIT $limit",
        [
            'category_id' => $product['category_id'],
            'slug'        => $product['slug']
        ]
    );
}