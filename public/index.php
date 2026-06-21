<?php

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost:8000');

/**
 * Load Core theo thứ tự đúng
 */
require_once BASE_PATH . '/app/core/Define.php';
require_once BASE_PATH . '/app/core/App.php';
require_once BASE_PATH . '/app/core/Router.php';
require_once BASE_PATH . '/app/core/Request.php';
require_once BASE_PATH . '/app/core/Response.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/View.php';
require_once BASE_PATH . '/app/core/Validator.php';
require_once BASE_PATH . '/app/core/Session.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/Middleware.php';

/**
 * Boot App
 */

$app = new App();
$app->run();