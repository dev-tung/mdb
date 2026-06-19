<?php

if (!function_exists('dump_data')) {
    function dump_data(...$vars)
    {
        echo "<pre>";
        foreach ($vars as $var) {
            print_r($var);
            echo "\n";
        }
        echo "</pre>";
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        dump_data(...$vars);
        die;
    }
}

if (!function_exists('base_url')) {
    function base_url()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            ? "https://"
            : "http://";

        return $protocol . ($_SERVER['HTTP_HOST'] ?? '');
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('current_url')) {
    function current_url($includeQuery = true)
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        if (!$includeQuery) {
            $uri = strtok($uri, '?');
        }

        return base_url() . $uri;
    }
}

if (!function_exists('active_menu')) {
    function active_menu(string $path = ''): string
    {
        $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return ($current === $path) ? 'active' : '';
    }
}

if (!function_exists('json_response')) {
    /**
     * Trả JSON và dừng script
     * @param mixed $data Dữ liệu muốn trả về
     * @param int $status HTTP status code (mặc định 200)
     */
    function json_response($data = [], int $status = 200)
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('request_input')) {
    /**
     * Lấy dữ liệu request, tự động merge GET, POST, PUT, DELETE, PATCH JSON
     * @return array Dữ liệu request
     */
    function request_input(): array
    {
        $data = [];

        // Lấy dữ liệu GET
        if (!empty($_GET)) {
            $data = $_GET;
        }

        // Lấy dữ liệu method khác
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // Nếu là POST với form hoặc JSON
        if ($method === 'POST') {
            if (str_contains($contentType, 'application/json')) {
                $postData = json_decode(file_get_contents('php://input'), true);
                if (is_array($postData)) {
                    $data = array_merge($data, $postData);
                }
            } else {
                // POST form-data hoặc x-www-form-urlencoded
                $data = array_merge($data, $_POST);
            }
        }

        // Các method PUT, DELETE, PATCH thường dùng JSON body
        if (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
            if (str_contains($contentType, 'application/json')) {
                $inputData = json_decode(file_get_contents('php://input'), true);
                if (is_array($inputData)) {
                    $data = array_merge($data, $inputData);
                }
            }
        }

        return $data;
    }
}

if (!function_exists('pager')) {

    function pager(array $params = [])
    {
        static $config = null;

        if ($config === null) {
            $config = [
                'window' => 2
            ];
        }

        if (empty($params)) {
            return $config;
        }

        $page  = $params['page'] ?? 1;
        $total = $params['total'] ?? 1;
        $query = $params['query'] ?? [];

        $color = ($params['color'] ?? false) ? 'success' : 'secondary';

        $build = function ($p) use ($query) {
            $query['page'] = $p;
            return '?' . http_build_query($query);
        };

        ob_start();
        ?>

        <?php if ($total > 1): ?>
            <nav class="mt-3 d-flex">
                <ul class="pagination pagination-sm shadow-sm mb-0">

                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link text-<?= $color ?>" href="<?= $build(1) ?>">« Đầu</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link text-<?= $color ?>" href="<?= $build($page - 1) ?>">‹</a>
                        </li>
                    <?php endif; ?>

                    <?php
                        $start = max(1, $page - $config['window']);
                        $end   = min($total, $page + $config['window']);
                    ?>

                    <?php if ($start > 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link text-<?= $color ?>">...</span>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">

                            <?php if ($i == $page): ?>
                                <span class="page-link border-<?= $color ?> text-<?= $color ?> bg-light">
                                    <?= $i ?>
                                </span>
                            <?php else: ?>
                                <a class="page-link text-<?= $color ?>" href="<?= $build($i) ?>">
                                    <?= $i ?>
                                </a>
                            <?php endif; ?>

                        </li>
                    <?php endfor; ?>

                    <?php if ($end < $total): ?>
                        <li class="page-item disabled">
                            <span class="page-link text-<?= $color ?>">...</span>
                        </li>
                    <?php endif; ?>

                    <?php if ($page < $total): ?>
                        <li class="page-item">
                            <a class="page-link text-<?= $color ?>" href="<?= $build($page + 1) ?>">›</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link text-<?= $color ?>" href="<?= $build($total) ?>">
                                Cuối »
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }
}


if (!function_exists('option')) {
    /**
     * Lấy hằng số từ config/option.php
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function option(?string $key = null, $default = null)
    {
        static $constants = null;

        if ($constants === null) {
            $constants = require __DIR__ . '/option.php';
        }

        if ($key === null) {
            return $constants;
        }

        // Hỗ trợ key dạng "order_status.pending"
        $keys = explode('.', $key);
        $value = $constants;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value;
    }
}

function array_merge_flat(array $arrays): array
{
    return array_merge(...array_values($arrays));
}

function array_paginate(array $items, int $page, int $perPage): array
{
    $total = count($items);
    $totalPages = max(1, ceil($total / $perPage));

    $page = max(1, min($page, $totalPages));

    return [
        'data' => array_slice($items, ($page - 1) * $perPage, $perPage),
        'page' => $page,
        'totalPages' => $totalPages
    ];
}

function build_query(array $extra = []): string
{
    return '?' . http_build_query(array_merge($_GET, $extra));
}

function get_query(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

function get_array(string $key): array
{
    return (array)($_GET[$key] ?? []);
}
