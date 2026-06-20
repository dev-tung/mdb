<?php

function get_finance_category_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_category
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}

function get_finance_category_by_name(string $name): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_category
         WHERE name = ?
         LIMIT 1",
        [$name]
    );
}

function get_finance_categories(): array
{
    return DB::all(
        "SELECT *
         FROM finance_category
         ORDER BY sort_order ASC, id DESC"
    );
}

/* =========================
   ACTIVE
========================= */

function get_finance_categories_active(): array
{
    return DB::all(
        "SELECT *
         FROM finance_category
         WHERE status = 1
         ORDER BY sort_order ASC, id DESC"
    );
}

/* =========================
   SEARCH
========================= */

function search_finance_categories(string $keyword): array
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
            OR type LIKE ?
        )";

        $search = "%{$word}%";

        $params[] = $search;
        $params[] = $search;
    }

    $whereSql = implode(' AND ', $whereClauses);

    return DB::all(
        "SELECT *
         FROM finance_category
         WHERE {$whereSql}
         ORDER BY sort_order ASC, name ASC",
        $params
    );
}

/* =========================
   DETAIL
========================= */

function get_finance_category_detail(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_category
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}