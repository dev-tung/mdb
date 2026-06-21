<?php

class View
{
    protected static string $module = 'website';

    // =========================
    // SET MODULE CONTEXT
    // =========================
    public static function setModule(string $module): void
    {
        self::$module = $module;
    }

    public static function module(): string
    {
        return self::$module;
    }

    // =========================
    // RENDER FULL PAGE
    // =========================
    public static function render(string $view, array $data = []): void
    {
        $basePath = self::basePath();
        $content  = "{$basePath}/{$view}.php";
        $header   = "{$basePath}/layouts/header.php";
        $footer   = "{$basePath}/layouts/footer.php";

        if (!file_exists($content)) {
            self::error("View not found", $content);
            return;
        }

        extract($data);

        self::startBuffer();

        try {

            self::safeRequire($header);
            self::safeRequire($content);
            self::safeRequire($footer);

            self::endBuffer();

        } catch (Throwable $e) {

            self::cleanBuffer();

            self::handleError($e, $content);
        }
    }

    // =========================
    // PATH RESOLVER
    // =========================
    protected static function basePath(): string
    {
        return BASE_PATH . "/app/modules/" . self::$module . "/views";
    }

    // =========================
    // SAFE INCLUDE FILE
    // =========================
    protected static function safeRequire(string $file): void
    {
        if ($file && file_exists($file)) {
            require $file;
        }
    }

    // =========================
    // OUTPUT BUFFER CONTROL
    // =========================
    protected static function startBuffer(): void
    {
        ob_start();
    }

    protected static function endBuffer(): void
    {
        ob_end_flush();
    }

    protected static function cleanBuffer(): void
    {
        if (ob_get_length()) {
            ob_end_clean();
        }
    }

    // =========================
    // ERROR HANDLING
    // =========================
    protected static function handleError(Throwable $e, string $file): void
    {
        http_response_code(500);

        echo "<h1 style='color:red'>VIEW ERROR</h1>";

        echo "<pre>";
        echo "File: {$file}\n";
        echo "Message: {$e->getMessage()}\n";
        echo "Line: {$e->getLine()}\n";
        echo "</pre>";
    }

    protected static function error(string $message, string $file): void
    {
        http_response_code(500);

        echo "<h1 style='color:red'>VIEW ERROR</h1>";
        echo "<pre>{$message}: {$file}</pre>";
    }
}