<?php

function dd(...$vars): void
{
    echo '<pre>';

    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';

    die();
}



function dump(...$vars): void
{
    echo '<pre>';

    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';
}