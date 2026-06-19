<?php

function get_customer_groups(): array
{
    return db_all(
        "SELECT *
         FROM customer_group
         ORDER BY name ASC"
    );
}

function get_customer_group_by_id(int $id): ?array
{
    return db_one(
        "SELECT *
         FROM customer_group
         WHERE id = :id
         LIMIT 1",
        ['id' => $id]
    );
}