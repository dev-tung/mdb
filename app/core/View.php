<?php

class View
{
    protected static string $module = 'website';

    public static function setModule(string $module): void
    {
        self::$module = $module;
    }

    public static function module(): string
    {
        return self::$module;
    }

    public static function render(string $view, array $data = []): void
    {
        $basePath = self::basePath();

        $header   = "{$basePath}/layouts/header.php";
        $content  = "{$basePath}/{$view}.php";
        $footer   = "{$basePath}/layouts/footer.php";

        if (!file_exists($content)) {
            self::fail("View not found: {$content}");
            return;
        }

        extract($data);

        require $header;
        require $content;
        require $footer;
    }

    protected static function basePath(): string
    {
        return BASE_PATH . "/app/modules/" . self::$module . "/views";
    }

    protected static function fail(string $message): void
    {
        http_response_code(500);
        echo "<pre>{$message}</pre>";
    }
}