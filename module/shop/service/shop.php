<?php

function get_shop_options(): array
{
    static $options;

    if ($options === null) {
        $options = require PATH_SHOP . 'config/option.php';
    }

    return $options;
}

function shop_option(string $key, mixed $default = []): mixed
{
    return get_shop_options()[$key] ?? $default;
}