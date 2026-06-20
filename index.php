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
    $modules = [
        'shop',
        'customer',
        'academy',
        'booking',
        'finance',
        'human',
        'legal',
        'stringing',
        'website'
    ];

    $routes = [];

    foreach ($modules as $module) {
        $path = PATH_ROOT . "/module/{$module}/route";

        if (file_exists($path . '/web.php')) {
            $routes = array_merge(
                $routes,
                require $path . '/web.php'
            );
        }

        if (file_exists($path . '/api.php')) {
            $routes = array_merge(
                $routes,
                require $path . '/api.php'
            );
        }
    }

    return $routes;
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

    if( str_starts_with($request, 'admin') ){
        require_once PATH_VIEW . '/layout/admin/header.php';
        require_once PATH_ROOT . '/' . $route['path'];
        require_once PATH_VIEW . '/layout/admin/footer.php';
    }else{
        require_once PATH_VIEW . '/layout/website/header.php';
        require_once PATH_ROOT . '/' . $route['path'];
        require_once PATH_VIEW . '/layout/website/footer.php';
    }
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