<?php

class View
{
    // =========================
    // CURRENT MODULE (website/admin/...)
    // =========================
    protected static string $module = 'website';

    // Set module context (switch layout + view folder)
    public static function setModule(string $module): void
    {
        self::$module = $module;
    }

    // Get current module name
    public static function module(): string
    {
        return self::$module;
    }

    // =========================
    // RENDER VIEW (HEADER + CONTENT + FOOTER)
    // =========================
    public static function render(string $view, array $data = []): void
    {
        $basePath = self::basePath();

        // Layout files
        $header  = "{$basePath}/layouts/header.php";
        $content = "{$basePath}/{$view}.php";
        $footer  = "{$basePath}/layouts/footer.php";

        // Check main view exists
        if (!file_exists($content)) {
            self::fail("View not found: {$content}");
            return;
        }

        // Extract data to variables for view
        extract($data);

        // Render layout flow
        require $header;
        require $content;
        require $footer;
    }

    // =========================
    // BUILD BASE PATH BY MODULE
    // =========================
    protected static function basePath(): string
    {
        return BASE_PATH . "/app/modules/" . self::$module . "/views";
    }

    // =========================
    // SIMPLE ERROR HANDLING
    // =========================
    protected static function fail(string $message): void
    {
        http_response_code(500);
        echo "<pre>{$message}</pre>";
    }
}