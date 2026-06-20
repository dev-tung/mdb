<?php

/* ==================================================
 * RESPONSE
 * ================================================== */

/**
 * Trả JSON response.
 */
function json_response(
    $data = [],
    int $status = 200
): void
{
    http_response_code($status);

    header(
        'Content-Type: application/json'
    );

    echo json_encode($data);

    exit;
}

/**
 * Trả response thành công.
 */
function response_success(
    array $data = []
): void
{
    json_response([
        'success' => true,
        ...$data
    ]);
}

/**
 * Trả response lỗi.
 */
function response_error(
    string $message,
    int $code = 400
): void
{
    json_response([
        'success' => false,
        'message' => $message
    ], $code);
}