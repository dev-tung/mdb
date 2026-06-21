<?php

class Middleware
{
    public static function fakeAuth(): array
    {
        return [
            'id' => 1,
            'name' => 'Dev Tung',
            'role' => 'admin'
        ];
    }

    public static function auth(): void
    {
        // luôn "cho qua" nhưng có user giả
        $GLOBALS['user'] = self::fakeAuth();
    }

    public static function admin(): void
    {
        $user = $GLOBALS['user'] ?? null;

        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            exit('Forbidden - Admin only');
        }
    }
}