<?php
class Middleware
{
    protected static array $moduleAuthMap = [
        'admin'   => 'admin',
        'crm'     => 'admin',
        'shop'    => 'admin',
        'website' => null
    ];

    public static function handle(string $module, array $routeMiddleware = [])
    {
        $authType = self::$moduleAuthMap[$module] ?? null;

        if ($authType === 'admin') {
            if (!Session::get('auth_user')) {
                http_response_code(401);
                echo json_encode(['message' => 'Unauthorized admin']);
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