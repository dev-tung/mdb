<?php
class Middleware
{
    protected static array $authMap = [
        // 'admin'   => 'admin',
        // 'crm'     => 'admin',
        // 'shop'    => 'admin',
        // 'website' => null
    ];

    public static function handle(string $module, array $routeMiddleware = [])
    {
        $authType = self::$authMap[$module] ?? null;

        if ($authType === 'admin') {
            if (!Session::get('auth_user')) {
              header('Location: /admin/login');
              exit;
            }
        }

        if ($authType === 'customer') {
            if (!Session::get('auth_customer')) {
                http_response_code(401);
                echo json_encode(['message' => 'Unauthorized customer']);
                exit;
            }
        }

        // route middleware custom (nếu có sau này)
    }
}