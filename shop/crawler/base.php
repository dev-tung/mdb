<?php

function crawler_log(string $message): void
{
    if (php_sapi_name() === 'cli') {
        echo $message . PHP_EOL;
    } else {
        echo $message . '<br>';
    }

    @ob_flush();
    flush();
}

function crawler_get_html(string $url): string
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_ENCODING => '',
    ]);

    $html = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    crawler_log("HTTP CODE: $status");

    if ($error) {
        crawler_log("CURL ERROR: $error");
    }

    return $html ?: '';
}

function crawler_download_image(string $url, string $path): bool
{
    $fp = fopen($path, 'wb');

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 120,
    ]);

    $result = curl_exec($ch);

    curl_close($ch);
    fclose($fp);

    return (bool) $result;
}

function crawler_delete_directory(string $dir): void
{
    if (!is_dir($dir)) return;

    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . '/' . $item;

        if (is_dir($path)) {
            crawler_delete_directory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($dir);
}