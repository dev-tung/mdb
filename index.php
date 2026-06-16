<?php

$route = trim($_GET['route'] ?? '', '/');

/**
 * Trang chủ
 */
if ($route === '') {

    $file = __DIR__ . '/home.php';

    if (file_exists($file)) {
        require $file;
        exit;
    }

    http_response_code(404);
    exit('home.php not found');
}

/**
 * Tách URL
 */
$segments = explode('/', $route);

$module = $segments[0];
$action = $segments[1] ?? 'index';

/**
 * modules/finance/index.php
 * modules/finance/report.php
 */
$file = __DIR__ . "/modules/{$module}/{$action}.php";

if (file_exists($file)) {
    require $file;
    exit;
}

http_response_code(404);
echo '404 Not Found';