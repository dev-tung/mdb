<?php

/* ==================================================
 * ARRAY
 * ================================================== */

/**
 * Gộp nhiều mảng thành một.
 */
function array_merge_flat(
    array $arrays
): array {
    return array_merge(
        ...array_values($arrays)
    );
}