<?php

require_once PATH_SHOP . 'repository/export.php';

function export_service(): array
{
    $exports = get_exports();



    $ctx = export_context();

    $exports = export_filter($exports, $ctx);



    $totalAmount = array_sum(
        array_map(
            fn($item) => (float) ($item['total_amount'] ?? 0),
            $exports
        )
    );

    $paged = array_paginate(
        $exports,
        $ctx['page'],
        20
    );

    return [
        'exports'      => $paged['data'],
        'page'         => $paged['page'],
        'totalPages'   => $paged['totalPages'],
        'totalAmount'  => $totalAmount,
        'ctx'          => $ctx,
    ];
}

function export_context(): array
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

function export_filter(array $exports, array $ctx): array
{
    return array_values(array_filter(
        $exports,
        function ($item) use ($ctx) {

            if (
                $ctx['keyword']
                && stripos(
                    $item['customer_name'] ?? '',
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

function export_amount($amount): string
{
    return number_format((float) $amount) . ' ₫';
}

function export_created_at(array $item): string
{
    return !empty($item['created_at'])
        ? date('d/m/Y H:i', strtotime($item['created_at']))
        : '-';
}
