<?php

/* ==================================================
 * URL
 * ================================================== */

/**
 * Trả về domain hiện tại.
 *
 * Ví dụ:
 * https://mdb.vn
 */
function base_url(): string
{
    $protocol = (
        !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off'
    )
        ? 'https://'
        : 'http://';

    return $protocol . ($_SERVER['HTTP_HOST'] ?? '');
}

/**
 * Tạo URL tuyệt đối.
 *
 * Ví dụ:
 * url('admin/product')
 */
function url(string $path = ''): string
{
    return rtrim(base_url(), '/')
        . '/'
        . ltrim($path, '/');
}

/**
 * Lấy URL hiện tại.
 */
function current_url(
    bool $includeQuery = true
): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    if (!$includeQuery) {
        $uri = strtok($uri, '?');
    }

    return base_url() . $uri;
}

/**
 * Build query string từ URL hiện tại.
 *
 * Ví dụ:
 * build_query(['page' => 2])
 */
function build_query(array $extra = []): string
{
    return '?'
        . http_build_query(
            array_merge($_GET, $extra)
        );
}