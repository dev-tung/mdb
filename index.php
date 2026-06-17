<?php
require_once __DIR__ . '/define.php';

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($request === '') {
    $request = 'home';
}

$segments = explode('/', $request);

require_once __DIR__ . '/start.php';

/* =========================
   SHOP ROUTE (GỘP 2 PATH)
========================= */
if ($segments[0] === 'shop') {

    // /shop/product/astrox-99-game
    if (isset($segments[1]) && $segments[1] === 'product' && isset($segments[2])) {

        $_GET['slug'] = $segments[2];

        require __DIR__ . '/shop/product-detail.php';
    }else {
        require __DIR__ . '/shop/product.php';
    }
}

else {
    http_response_code(404);
    echo "404 Not Found";
}

require_once __DIR__ . '/end.php';