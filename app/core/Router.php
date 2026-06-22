<?php

class Router
{
    protected static array $routes = [];

    // =========================
    // REGISTER GET
    // =========================
    public static function get(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['GET'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    // =========================
    // REGISTER POST
    // =========================
    public static function post(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['POST'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    // =========================
    // DISPATCH
    // =========================
    public static function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach (self::$routes[$method] ?? [] as $route) {

            $pattern = self::convertUriToRegex($route['uri']);

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);

                // 1. resolve controller file
                $controllerFile = self::resolveControllerFile($route['handler']);

                if (!$controllerFile) {
                    die("Controller not found: {$route['handler']}");
                }

                // 2. detect module from file path
                $module = self::detectModuleFromPath($controllerFile);

                // 3. set view context (IMPORTANT)
                View::setModule($module);

                // 4. middleware (placeholder)
                // self::handleMiddleware($route['middleware']);

                // 5. call controller
                self::callAction($route['handler'], $controllerFile, $matches);

                return;
            }
        }

        http_response_code(404);
        echo "404 NOT FOUND: $uri";
    }

    // =========================
    // CONVERT ROUTE PARAMS
    // =========================
    protected static function convertUriToRegex(string $uri): string
    {
        $pattern = preg_replace_callback(
            '#\{([a-zA-Z_]+)\}#',
            function () {
                return '([a-zA-Z0-9_-]+)';
            },
            $uri
        );

        return "#^" . $pattern . "$#";
    }

    // =========================
    // CALL CONTROLLER ACTION
    // =========================
    protected static function callAction(string $handler, string $file, array $params = []): void
    {
        [$controller, $action] = explode('@', $handler);

        if (!class_exists($controller)) {
            die("Class not found: {$controller}");
        }

        $instance = new $controller();

        call_user_func_array([$instance, $action], $params);
    }

    // =========================
    // RESOLVE CONTROLLER FILE
    // =========================
    protected static function resolveControllerFile(string $handler): ?string
    {
        [$controller] = explode('@', $handler);

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

    // =========================
    // DETECT MODULE FROM PATH
    // =========================
    protected static function detectModuleFromPath(string $file): string
    {
        // .../modules/website/controllers/HomeController.php

        $parts = explode('/modules/', $file);

        $sub = explode('/', $parts[1]);

        return $sub[0] ?? 'website';
    }
}