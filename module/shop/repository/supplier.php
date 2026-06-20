<?php

function get_supplier_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM shop_supplier
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}

function get_supplier_by_phone(string $phone): ?array
{
    return DB::row(
        "SELECT *
         FROM shop_supplier
         WHERE phone = ?
         LIMIT 1",
        [$phone]
    );
}

function get_supplier_by_email(string $email): ?array
{
    return DB::row(
        "SELECT *
         FROM shop_supplier
         WHERE email = ?
         LIMIT 1",
        [$email]
    );
}

/* =========================
   LIST
========================= */

function get_suppliers(): array
{
    return DB::all(
        "SELECT
            id,
            name,
            phone,
            address,
            description,
            email,
            created_at,
            updated_at
         FROM shop_supplier
         ORDER BY id DESC"
    );
}

/* =========================
   SEARCH
========================= */

function search_suppliers(string $keyword): array
{
    $keyword = trim(preg_replace('/\s+/', ' ', $keyword));

    if ($keyword === '') {
        return [];
    }

    $words = explode(' ', $keyword);

    $whereClauses = [];
    $params = [];

    foreach ($words as $word) {

        $whereClauses[] = "(
            name LIKE ?
            OR phone LIKE ?
            OR email LIKE ?
            OR address LIKE ?
            OR description LIKE ?
        )";

        $search = "%{$word}%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereSql = implode(' AND ', $whereClauses);

    return DB::all(
        "SELECT
            id,
            name,
            phone,
            address,
            description,
            email,
            created_at,
            updated_at
         FROM shop_supplier
         WHERE {$whereSql}
         ORDER BY name ASC",
        $params
    );
}

/* =========================
   DETAIL
========================= */

function get_supplier_detail(int $id): ?array
{
    return DB::row(
        "SELECT
            id,
            name,
            phone,
            address,
            description,
            email,
            created_at,
            updated_at
         FROM shop_supplier
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}