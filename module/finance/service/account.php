<?php
require_once PATH_FINANCE . 'repository/account.php';

/* =========================
   SERVICE
========================= */

function finance_account_service(): array
{
    $ctx = finance_account_context();

    $accounts = get_finance_accounts();

    $accounts = finance_account_filter($accounts, $ctx);

    $paged = array_paginate(
        $accounts,
        $ctx['page'],
        $ctx['perPage']
    );

    return [
        'accounts'   => $paged['data'],
        'page'       => $paged['page'],
        'totalPages' => $paged['totalPages'],
        'filters'    => $ctx
    ];
}

/* =========================
   CONTEXT
========================= */

function finance_account_context(): array
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
   FILTER
========================= */

function finance_account_filter(array $accounts, array $ctx): array
{
    $keyword = mb_strtolower($ctx['keyword'] ?? '');
    $type    = (string) ($ctx['type'] ?? '');
    $status  = (int) ($ctx['status'] ?? -1);

    return array_values(array_filter($accounts, function ($a) use ($keyword, $type, $status) {

        if ($keyword !== '') {

            $haystack = mb_strtolower(
                ($a['name'] ?? '') . ' ' .
                ($a['type'] ?? '') . ' ' .
                ($a['note'] ?? '')
            );

            if (!str_contains($haystack, $keyword)) {
                return false;
            }
        }

        if ($type !== '' && ($a['type'] ?? '') !== $type) {
            return false;
        }

        if ($status !== -1 && (int)($a['status'] ?? 0) !== $status) {
            return false;
        }

        return true;
    }));
}

/* =========================
   HELPERS
========================= */

function finance_account_name($a): string
{
    return $a['name'] ?? '';
}

function finance_account_type($a): string
{
    return $a['type'] ?? '';
}

function finance_account_balance($a): float
{
    return (float)($a['initial_balance'] ?? 0);
}

function finance_account_note($a): string
{
    return $a['note'] ?? '';
}

/* =========================
   INDEX
========================= */

function finance_account_index($page, $index): int
{
    $page  = max(1, (int)$page);
    $index = (int)$index;

    return (($page - 1) * 20) + $index + 1;
}