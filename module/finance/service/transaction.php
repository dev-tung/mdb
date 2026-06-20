<?php
require_once PATH_FINANCE . 'repository/transaction.php';

function finance_transaction_service(): array
{
    $ctx = finance_transaction_context();

    $result = finance_transaction_query($ctx);

    return [
        'transactions' => $result['data'],
        'page'         => $result['page'],
        'totalPages'   => $result['totalPages'],
        'filters'      => $ctx
    ];
}

function finance_transaction_context(): array
{
    return [
        'keyword'     => trim((string) get_query('keyword', '')),
        'account_id'  => (string) get_query('account_id', ''),   // FIX: để string cho dễ filter
        'category_id' => (string) get_query('category_id', ''),
        'module'      => (string) get_query('module', ''),
        'page'        => max(1, (int) get_query('page', 1)),
        'perPage'     => 20,
    ];
}

function finance_transaction_query(array $ctx): array
{
    $page    = (int)$ctx['page'];
    $perPage = (int)$ctx['perPage'];
    $offset  = ($page - 1) * $perPage;

    $where  = "WHERE 1=1";
    $params = [];

    // keyword
    if ($ctx['keyword'] !== '') {
        $where .= " AND (
            t.note LIKE ? OR
            t.module LIKE ?
        )";

        $kw = "%" . $ctx['keyword'] . "%";
        $params[] = $kw;
        $params[] = $kw;
    }

    // account
    if ($ctx['account_id'] !== '') {
        $where .= " AND t.account_id = ?";
        $params[] = $ctx['account_id'];
    }

    // category
    if ($ctx['category_id'] !== '') {
        $where .= " AND t.category_id = ?";
        $params[] = $ctx['category_id'];
    }

    // module
    if ($ctx['module'] !== '') {
        $where .= " AND t.module = ?";
        $params[] = $ctx['module'];
    }

    // total
    $total = DB::row("
        SELECT COUNT(*) as total
        FROM finance_transaction t
        $where
    ", $params)['total'] ?? 0;

    $totalPages = ceil($total / $perPage);

    // data + JOIN
    $data = DB::all("
        SELECT 
            t.*,
            a.name AS account_name,
            c.name AS category_name
        FROM finance_transaction t
        LEFT JOIN finance_account a ON a.id = t.account_id
        LEFT JOIN finance_category c ON c.id = t.category_id
        $where
        ORDER BY t.id DESC
        LIMIT $perPage OFFSET $offset
    ", $params);

    return [
        'data'       => $data,
        'page'       => $page,
        'totalPages' => $totalPages
    ];
}

function finance_transaction_index($page, $index): int
{
    return (($page - 1) * 20) + $index + 1;
}