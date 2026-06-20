<?php
require_once PATH_CUSTOMER. 'repository/customer.php';

/* =========================
   SERVICE
========================= */

function customer_service(): array
{
    $groups = get_customer_groups();
    $ctx    = customer_context();

    // lấy data từ repo (đã join group)
    $customers = get_customers_with_group();

    // filter
    $customers = customer_filter($customers, $ctx);

    // paginate
    $paged = array_paginate(
        $customers,
        $ctx['page'],
        $ctx['perPage']
    );

    return [
        'customers'  => $paged['data'],
        'groups'     => $groups,
        'page'       => $paged['page'],
        'totalPages' => $paged['totalPages'],
        'filters'    => $ctx
    ];
}

/* =========================
   CONTEXT
========================= */

function customer_context(): array
{
    return [
        'keyword' => trim((string) get_query('keyword', '')),
        'group'   => (int) get_query('group', 0),
        'page'    => max(1, (int) get_query('page', 1)),
        'perPage' => 20,
    ];
}

/* =========================
   FILTER
========================= */

function customer_filter(array $customers, array $ctx): array
{
    $keyword = mb_strtolower($ctx['keyword'] ?? '');
    $group   = (int) ($ctx['group'] ?? 0);

    return array_values(array_filter($customers, function ($c) use ($keyword, $group) {

        // keyword search
        if ($keyword !== '') {

            $haystack = mb_strtolower(
                ($c['name'] ?? '') . ' ' .
                ($c['phone'] ?? '') . ' ' .
                ($c['email'] ?? '') . ' ' .
                ($c['address'] ?? '')
            );

            if (!str_contains($haystack, $keyword)) {
                return false;
            }
        }

        // group filter
        if ($group > 0 && (int)($c['group_id'] ?? 0) !== $group) {
            return false;
        }

        return true;
    }));
}

/* =========================
   QUERY BUILDER
========================= */

function customer_build_query(array $extra = []): string
{
    return build_query($extra);
}

/* =========================
   FORMAT HELPERS
========================= */

function customer_name($c): string
{
    return $c['name'] ?? '';
}

function customer_phone($c): string
{
    return $c['phone'] ?? '';
}

function customer_email($c): string
{
    return $c['email'] ?? '';
}

function customer_address($c): string
{
    return $c['address'] ?? '';
}

/* =========================
   GROUP NAME
========================= */

function customer_group_name($groupId, $groups): string
{
    $groupId = (int) $groupId;

    foreach ($groups as $g) {
        if ((int)($g['id'] ?? 0) === $groupId) {
            return $g['name'] ?? 'Chưa phân loại';
        }
    }

    return 'Chưa phân loại';
}

/* =========================
   PAGINATION INDEX
========================= */

function customer_index($page, $index): int
{
    $page  = max(1, (int)$page);
    $index = (int)$index;

    return (($page - 1) * 20) + $index + 1;
}