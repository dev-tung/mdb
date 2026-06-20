<?php

function get_finance_account_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_account
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}

function get_finance_account_by_name(string $name): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_account
         WHERE name = ?
         LIMIT 1",
        [$name]
    );
}

function get_finance_accounts(): array
{
    return DB::all(
        "SELECT *
         FROM finance_account
         ORDER BY id DESC"
    );
}

/* =========================
   ACTIVE / STATUS
========================= */

function get_finance_accounts_active(): array
{
    return DB::all(
        "SELECT *
         FROM finance_account
         WHERE status = 1
         ORDER BY id DESC"
    );
}

/* =========================
   SEARCH
========================= */

function search_finance_accounts(string $keyword): array
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
            OR note LIKE ?
        )";

        $search = "%{$word}%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereSql = implode(' AND ', $whereClauses);

    return DB::all(
        "SELECT *
         FROM finance_account
         WHERE {$whereSql}
         ORDER BY name ASC",
        $params
    );
}

/* =========================
   DETAIL
========================= */

function get_finance_account_detail(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_account
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}