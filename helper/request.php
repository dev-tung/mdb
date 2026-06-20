<?php

/* ==================================================
 * REQUEST
 * ================================================== */

/**
 * Lấy toàn bộ dữ liệu request.
 *
 * Hỗ trợ:
 * - GET
 * - POST form-data
 * - POST JSON
 * - PUT JSON
 * - PATCH JSON
 * - DELETE JSON
 */
function request_input(): array
{
    $data = [];

    if (!empty($_GET)) {
        $data = $_GET;
    }

    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if ($method === 'POST') {

        if (str_contains($contentType, 'application/json')) {

            $body = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (is_array($body)) {
                $data = array_merge($data, $body);
            }

        } else {

            $data = array_merge(
                $data,
                $_POST
            );
        }
    }

    if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {

        if (str_contains($contentType, 'application/json')) {

            $body = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (is_array($body)) {
                $data = array_merge($data, $body);
            }
        }
    }

    return $data;
}

/**
 * Lấy giá trị từ query string.
 *
 * Ví dụ:
 * ?page=2
 *
 * get_query('page', 1)
 * => 2
 */
function get_query(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

/**
 * Lấy mảng từ query string.
 *
 * Ví dụ:
 * ?brand[]=1&brand[]=2
 *
 * get_array('brand')
 * => [1,2]
 */
function get_array(string $key): array
{
    return (array) ($_GET[$key] ?? []);
}