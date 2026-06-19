<?php

function get_customer_groups(): array
{
    return DB::all(
        "SELECT *
         FROM customer_group
         ORDER BY name ASC"
    );
}

function get_customer_group_by_id(int $id): ?array
{
    return DB::row(
        "SELECT *
         FROM customer_group
         WHERE id = ?
         LIMIT 1",
        [$id]
    );
}