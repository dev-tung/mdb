<?php

/* =========================
   FIND BY ID
========================= */

function get_finance_transaction_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_transaction
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}

/* =========================
   LIST ALL
========================= */

function get_finance_transactions(): array
{
    return DB::all(
        "SELECT *
         FROM finance_transaction
         ORDER BY id DESC"
    );
}

/* =========================
   ACTIVE FILTER (optional)
========================= */

function get_finance_transactions_by_account(int $accountId): array
{
    return DB::all(
        "SELECT *
         FROM finance_transaction
         WHERE account_id = ?
         ORDER BY transaction_date DESC, id DESC",
        [$accountId]
    );
}

/* =========================
   SEARCH
========================= */

function search_finance_transactions(string $keyword): array
{
    $keyword = trim(preg_replace('/\s+/', ' ', $keyword));

    if ($keyword === '') {
        return [];
    }

    $words = explode(' ', $keyword);

    $where = [];
    $params = [];

    foreach ($words as $word) {

        $where[] = "(
            note LIKE ? OR
            module LIKE ? OR
            reference_type LIKE ?
        )";

        $like = "%{$word}%";

        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $sqlWhere = implode(' AND ', $where);

    return DB::all(
        "SELECT *
         FROM finance_transaction
         WHERE {$sqlWhere}
         ORDER BY transaction_date DESC",
        $params
    );
}

/* =========================
   DETAIL
========================= */

function get_finance_transaction_show(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM finance_transaction
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}