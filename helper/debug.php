<?php

/* ==================================================
 * DEBUG
 * ================================================== */

/**
 * Dump dữ liệu ra màn hình.
 */
function dump_data(...$vars): void
{
    echo '<pre>';

    foreach ($vars as $var) {
        print_r($var);
        echo PHP_EOL;
    }

    echo '</pre>';
}

/**
 * Dump dữ liệu và dừng chương trình.
 */
function dd(...$vars): void
{
    dump_data(...$vars);
    die;
}