<?php

require_once PATH_SHOP . 'repository/import.php';

function import_service(): array
{
    $imports = get_imports();

    $ctx = import_context();

    $imports = import_filter($imports, $ctx);

    $totalAmount = array_sum(
        array_map(
            fn($item) => (float) ($item['total_amount'] ?? 0),
            $imports
        )
    );

    $paged = array_paginate(
        $imports,
        $ctx['page'],
        20
    );

    return [
        'imports'      => $paged['data'],
        'page'         => $paged['page'],
        'totalPages'   => $paged['totalPages'],
        'totalAmount'  => $totalAmount,
        'ctx'          => $ctx,
    ];
}

function import_context(): array
{
    return [
        'keyword' => trim($_GET['keyword'] ?? ''),
        'status'  => $_GET['status'] ?? '',
        'payment' => $_GET['payment'] ?? '',
        'from'    => $_GET['from'] ?? '',
        'to'      => $_GET['to'] ?? '',
        'page'    => max(1, (int) ($_GET['page'] ?? 1)),
    ];
}

function import_filter(array $imports, array $ctx): array
{
    return array_values(array_filter(
        $imports,
        function ($item) use ($ctx) {

            if (
                $ctx['keyword']
                && stripos(
                    $item['supplier_name'] ?? '',
                    $ctx['keyword']
                ) === false
            ) {
                return false;
            }

            if (
                $ctx['status'] !== ''
                && ($item['status'] ?? '') != $ctx['status']
            ) {
                return false;
            }

            if (
                $ctx['payment'] !== ''
                && ($item['payment_status'] ?? '') != $ctx['payment']
            ) {
                return false;
            }

            if (
                $ctx['from']
                && strtotime($item['created_at'])
                < strtotime($ctx['from'])
            ) {
                return false;
            }

            if (
                $ctx['to']
                && strtotime($item['created_at'])
                > strtotime($ctx['to'] . ' 23:59:59')
            ) {
                return false;
            }

            return true;
        }
    ));
}

function import_amount($amount): string
{
    return number_format((float) $amount) . ' ₫';
}

function import_created_at(array $item): string
{
    return !empty($item['created_at'])
        ? date('d/m/Y H:i', strtotime($item['created_at']))
        : '-';
}