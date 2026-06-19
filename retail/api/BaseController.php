<?php

abstract class BaseController
{
    protected function json(
        mixed $data,
        int $status = 200
    ): void {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    protected function success(
        mixed $data = null
    ): void {
        $this->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    protected function error(
        string $message,
        int $status = 400
    ): void {
        $this->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}