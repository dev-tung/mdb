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

function get_featured_products(int $limit = 8): array
{
    return db_all(
        "SELECT *
         FROM shop_product
         WHERE status = 1
         AND category_id = 1
         ORDER BY id DESC
         LIMIT $limit"
    );
}

function search_products(string $keyword): array
{
    // 1. Làm sạch từ khóa, loại bỏ khoảng trắng thừa
    $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    if ($keyword === '') {
        return [];
    }

    // 2. Tách từ khóa thành các từ đơn lẻ bằng khoảng trắng
    $words = explode(' ', $keyword);
    
    // 3. Xây dựng câu lệnh WHERE động với tham số độc lập
    $whereClauses = [];
    $params = [];

    foreach ($words as $index => $word) {
        // Tạo 3 tên định danh tham số hoàn toàn riêng biệt cho 3 cột
        $pParam = "p_word_" . $index;
        $bParam = "b_word_" . $index;
        $cParam = "c_word_" . $index;

        // Ép các tham số độc lập vào chuỗi truy vấn để tránh lỗi PDO Driver
        $whereClauses[] = "(p.name LIKE :{$pParam} OR b.name LIKE :{$bParam} OR c.name LIKE :{$cParam})";
        
        // Gán giá trị tìm kiếm tương ứng
        $params[$pParam] = "%{$word}%";
        $params[$bParam] = "%{$word}%";
        $params[$cParam] = "%{$word}%";
    }

    // Gom toàn bộ điều kiện lọc bằng toán tử AND
    $whereSql = implode(' AND ', $whereClauses);

    // 4. Thực thi truy vấn SQL an toàn thông qua hàm db_all() của bạn
    return db_all(
        "SELECT p.*,
                b.name AS brand_name,
                c.name AS category_name
         FROM shop_product p
         LEFT JOIN shop_brand b ON b.id = p.brand_id
         LEFT JOIN shop_category c ON c.id = p.category_id
         WHERE p.status = 1
           AND {$whereSql}
         ORDER BY p.name ASC",
        $params
    );
}



function get_product_specs(int $productId): array
{
    $rows = db_all("
        SELECT spec_key, spec_value
        FROM shop_product_spec
        WHERE product_id = ?
    ", [$productId]);

    $specs = [];

    foreach ($rows as $row) {
        $specs[$row['spec_key']] = $row['spec_value'];
    }

    return $specs;
}

function get_product_images(int $productId): array
{
    $rows = db_all("
        SELECT image
        FROM shop_product_image
        WHERE product_id = ?
        ORDER BY sort_order ASC
    ", [$productId]);

    return array_column($rows, 'image');
}