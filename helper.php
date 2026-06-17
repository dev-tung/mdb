<?php

function array_merge_flat(array $arrays): array
{
    return array_merge(...array_values($arrays));
}

function array_paginate(array $items, int $page, int $perPage): array
{
    $total = count($items);
    $totalPages = max(1, ceil($total / $perPage));

    $page = max(1, min($page, $totalPages));

    return [
        'data' => array_slice($items, ($page - 1) * $perPage, $perPage),
        'page' => $page,
        'totalPages' => $totalPages
    ];
}

function build_query(array $extra = []): string
{
    return '?' . http_build_query(array_merge($_GET, $extra));
}

function get_query(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

function get_array(string $key): array
{
    return (array)($_GET[$key] ?? []);
}

