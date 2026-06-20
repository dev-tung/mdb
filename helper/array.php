<?php

/* ==================================================
 * ARRAY
 * ================================================== */

/**
 * Gộp nhiều mảng thành một mảng phẳng.
 *
 * Ví dụ:
 * [
 *   [1,2],
 *   [3,4]
 * ]
 *
 * =>
 * [1,2,3,4]
 */
function array_merge_flat(
    array $arrays
): array
{
    return array_merge(
        ...array_values($arrays)
    );
}