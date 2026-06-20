<?php

/* ==================================================
 * PAGINATION
 * ================================================== */

/**
 * Phân trang mảng dữ liệu.
 */
function array_paginate(
    array $items,
    int $page,
    int $perPage
): array
{
    $total = count($items);

    $totalPages = max(
        1,
        (int) ceil($total / $perPage)
    );

    $page = max(
        1,
        min($page, $totalPages)
    );

    return [
        'data' => array_slice(
            $items,
            ($page - 1) * $perPage,
            $perPage
        ),

        'page' => $page,

        'totalPages' => $totalPages
    ];
}