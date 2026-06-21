<?php

class Router
{
    protected static array $routes = [];

    /**
     * Register GET route
     */
    public static function get(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['GET'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * Register POST route
     */
    public static function post(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['POST'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch request
     */
    public static function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach (self::$routes[$method] ?? [] as $route) {

            $pattern = self::convertUriToRegex($route['uri']);

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // remove full match

                // 1. run middleware first
                self::runMiddleware($route['middleware'] ?? []);

                // 2. call controller
                self::callAction($route['handler'], $matches);

                return;
            }
        }

        http_response_code(404);
        echo "404 NOT FOUND: $uri";
    }

    /**
     * Convert /product/{id} → regex
     */
    protected static function convertUriToRegex(string $uri): string
    {
        $pattern = preg_replace(
            '#\{[a-zA-Z_]+\}#',
            '([a-zA-Z0-9_-]+)',
            $uri
        );

        return "#^" . $pattern . "$#";
    }

    /**
     * Middleware runner
     */
    protected static function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $mw) {

            if ($mw === 'auth') {
                Middleware::auth();
            }

            if ($mw === 'admin') {
                Middleware::admin();
            }
        }
    }

    /**
     * Call controller action
     */
    protected static function callAction(string $handler, array $params = []): void
    {
        [$controller, $action] = explode('@', $handler);

        $file = self::resolveControllerFile($controller);

        if (!$file) {
            die("Controller not found: {$controller}");
        }

        require_once $file;

        if (!class_exists($controller)) {
            die("Class not found: {$controller}");
        }

        $instance = new $controller();

        call_user_func_array([$instance, $action], $params);
    }

    /**
     * Auto resolve controller from ALL modules
     * supports controllers + endpoints
     */
    protected static function resolveControllerFile(string $controller): ?string
    {
        $modules = glob(BASE_PATH . '/app/modules/*', GLOB_ONLYDIR);

        foreach ($modules as $module) {

            $paths = [
                $module . "/controllers/{$controller}.php",
                $module . "/endpoints/{$controller}.php",
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        return null;
    }
}