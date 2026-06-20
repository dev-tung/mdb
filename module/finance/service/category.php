<?php
require_once PATH_FINANCE . 'repository/category.php';

/* =========================
   SERVICE
========================= */

function finance_category_service(): array
{
    $ctx = finance_category_context();

    $result = finance_category_query($ctx);

    return [
        'categories' => $result['data'],
        'page'       => $result['page'],
        'totalPages' => $result['totalPages'],
        'filters'    => $ctx
    ];
}

/* =========================
   CONTEXT
========================= */

function finance_category_context(): array
{
    return [
        'keyword' => trim((string) get_query('keyword', '')),
        'type'    => (string) get_query('type', ''),
        'status'  => (int) get_query('status', -1),
        'page'    => max(1, (int) get_query('page', 1)),
        'perPage' => 20,
    ];
}

/* =========================
   QUERY (SQL FILTER + PAGINATION)
========================= */

function finance_category_query(array $ctx): array
{
    $page    = max(1, (int)$ctx['page']);
    $perPage = (int)$ctx['perPage'];
    $offset  = ($page - 1) * $perPage;

    $where  = "WHERE 1=1";
    $params = [];

    // keyword
    if (!empty($ctx['keyword'])) {
        $where .= " AND (
            name LIKE ? OR
            type LIKE ?
        )";

        $kw = "%" . $ctx['keyword'] . "%";
        $params[] = $kw;
        $params[] = $kw;
    }

    // type
    if (!empty($ctx['type'])) {
        $where .= " AND type = ?";
        $params[] = $ctx['type'];
    }

    // status
    if ($ctx['status'] !== -1) {
        $where .= " AND status = ?";
        $params[] = $ctx['status'];
    }

    // total
    $total = DB::row(
        "SELECT COUNT(*) as total
         FROM finance_category
         $where",
        $params
    )['total'] ?? 0;

    $totalPages = ceil($total / $perPage);

    // data
    $data = DB::all(
        "SELECT *
         FROM finance_category
         $where
         ORDER BY sort_order ASC, id DESC
         LIMIT $perPage OFFSET $offset",
        $params
    );

    return [
        'data' => $data,
        'page' => $page,
        'totalPages' => $totalPages
    ];
}

/* =========================
   HELPERS
========================= */

function finance_category_name($c): string
{
    return $c['name'] ?? '';
}

function finance_category_type($c): string
{
    return $c['type'] ?? '';
}

function finance_category_status($c): int
{
    return (int)($c['status'] ?? 0);
}

function finance_category_index($page, $index): int
{
    $page  = max(1, (int)$page);
    $index = (int)$index;

    return (($page - 1) * 20) + $index + 1;
}