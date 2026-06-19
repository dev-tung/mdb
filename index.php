<?php

require_once __DIR__ . '/bootstrap.php';

/* =========================
   RESOLVE REQUEST
========================= */

function resolveRequest(): string
{
    $request = trim(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
        '/'
    );

    return $request === '' ? 'home' : $request;
}

/* =========================
   LOAD ROUTES
========================= */

function loadRoutes(): array
{
    return array_merge(
        require __DIR__ . '/route/web.php',
        require __DIR__ . '/route/api.php'
    );
}

/* =========================
   DISPATCH ROUTE
========================= */

function dispatchRoute(array $route): void
{
    // API
    if (isset($route['controller'])) {

        $controller = new $route['controller'];

        $action = $route['action'] ?? 'index';

        $controller->$action();

        return;
    }

    // WEB
    $request = resolveRequest();

    $header = str_starts_with($request, 'admin')
        ? 'navbar'
        : 'header';

    require_once __DIR__ . '/partial/start.php';
    require_once __DIR__ . '/partial/' . $header . '.php';
    require_once __DIR__ . '/' . $route['path'];
    require_once __DIR__ . '/partial/end.php';
}

/* =========================
   MATCH ROUTES
========================= */

function matchRoute(string $request, array $routes): bool
{
    foreach ($routes as $pattern => $route) {

        $regex = preg_replace(
            '#\{[a-zA-Z_]+\}#',
            '([^/]+)',
            $pattern
        );

        $regex = "#^{$regex}$#";

        if (!preg_match($regex, $request, $matches)) {
            continue;
        }

        array_shift($matches);

        if (!empty($route['params'])) {

            foreach ($route['params'] as $i => $key) {
                $_GET[$key] = $matches[$i] ?? null;
            }
        }

        dispatchRoute($route);

        return true;
    }

    return false;
}

/* =========================
   BOOT ROUTER
========================= */

$request = resolveRequest();
$routes  = loadRoutes();

if (!matchRoute($request, $routes)) {

    http_response_code(404);

    echo '404 Not Found';
}