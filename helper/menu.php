<?php

/* ==================================================
 * MENU
 * ================================================== */

/**
 * Active menu theo URL hiện tại.
 */
function active_menu(
    string $path = ''
): string {

    $current = parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

    return $current === $path
        ? 'active'
        : '';
}