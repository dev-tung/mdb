<?php

function get_customer_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM customer
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}

function get_customer_by_phone(string $phone): ?array
{
    return DB::row(
        "SELECT *
         FROM customer
         WHERE phone = ?
         LIMIT 1",
        [$phone]
    );
}

function get_customer_by_email(string $email): ?array
{
    return DB::row(
        "SELECT *
         FROM customer
         WHERE email = ?
         LIMIT 1",
        [$email]
    );
}

function get_customers(): array
{
    return DB::all(
        "SELECT *
         FROM customer
         ORDER BY id DESC"
    );
}

/* =========================
   ACTIVE / STATUS
========================= */

function get_customers_active(): array
{
    return DB::all(
        "SELECT *
         FROM customer
         ORDER BY id DESC"
    );
}

/* =========================
   SEARCH
========================= */

function search_customers(string $keyword): array
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
        )";

        $search = "%{$word}%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereSql = implode(' AND ', $whereClauses);

    return DB::all(
        "SELECT *
         FROM customer
         WHERE {$whereSql}
         ORDER BY name ASC",
        $params
    );
}

/* =========================
   JOIN GROUP
========================= */

function get_customers_with_group(): array
{
    return DB::all(
        "SELECT
            c.*,
            g.name AS group_name
         FROM customer c
         LEFT JOIN customer_group g
            ON g.id = c.group_id
         ORDER BY c.id DESC"
    );
}

function get_customer_detail(int $id): ?array
{
    return DB::row(
        "SELECT
            c.*,
            g.name AS group_name
         FROM customer c
         LEFT JOIN customer_group g
            ON g.id = c.group_id
         WHERE c.id = ?
         LIMIT 1",
        [$id]
    );
}