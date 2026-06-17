<?php
require_once __DIR__ . '/define.php';

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

/* nếu truy cập root "/" thì dùng home.php */
if ($request === '') {
    $request = 'home';
}

$file = __DIR__ . '/' . $request . '.php';

if (file_exists($file)) {
    require_once __DIR__ . '/start.php';
    require_once $file;
    require_once __DIR__ . '/end.php';
} else {
    http_response_code(404);
}