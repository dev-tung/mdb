<?php
require_once __DIR__ . '/bootstrap.php';

/* =========================
   RESOLVE REQUEST
========================= */
function resolveRequest(): string
{
    $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    return $request === '' ? 'home' : $request;
}

/* =========================
   DISPATCH ROUTE
========================= */
function dispatchRoute(array $route): void
{
    require_once __DIR__ . '/start.php';

    require __DIR__ . '/' . $route['path'];

    require_once __DIR__ . '/end.php';
}

/* =========================
   MATCH ROUTES
========================= */
function matchRoute(string $request, array $routes): bool
{
    foreach ($routes as $pattern => $route) {

        // convert /retail/product/{slug} -> regex
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $pattern);
        $regex = "#^" . $regex . "$#";

        if (!preg_match($regex, $request, $matches)) {
            continue;
        }

        array_shift($matches);

        // inject params
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
$routes = require __DIR__ . '/route.php';

if (!matchRoute($request, $routes)) {
    http_response_code(404);
    echo "404 Not Found";
}