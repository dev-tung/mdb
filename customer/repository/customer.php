<?php

function get_customer_by_id(int $id): ?array
{
    return db_one(
        "SELECT *
         FROM customer
         WHERE id = :id
         LIMIT 1",
        ['id' => $id]
    );
}

function get_customer_by_phone(string $phone): ?array
{
    return db_one(
        "SELECT *
         FROM customer
         WHERE phone = :phone
         LIMIT 1",
        ['phone' => $phone]
    );
}

function get_customer_by_email(string $email): ?array
{
    return db_one(
        "SELECT *
         FROM customer
         WHERE email = :email
         LIMIT 1",
        ['email' => $email]
    );
}

function get_customers(): array
{
    return db_all(
        "SELECT *
         FROM customer
         ORDER BY id DESC"
    );
}

/* =========================
   ACTIVE / STATUS
   (giữ linh hoạt, không ép ENUM)
========================= */

function get_customers_active(): array
{
    return db_all(
        "SELECT *
         FROM customer
         ORDER BY id DESC"
    );
}

/* =========================
   SEARCH
========================= */

function search_customers(string $keyword): array
{
    $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    if ($keyword === '') {
        return [];
    }

    $words = explode(' ', $keyword);

    $whereClauses = [];
    $params = [];

    foreach ($words as $index => $word) {

        $n = "name_" . $index;
        $p = "phone_" . $index;
        $e = "email_" . $index;

        $whereClauses[] = "(
            name LIKE :{$n}
            OR phone LIKE :{$p}
            OR email LIKE :{$e}
            OR address LIKE :{$n}
        )";

        $params[$n] = "%{$word}%";
        $params[$p] = "%{$word}%";
        $params[$e] = "%{$word}%";
    }

    $whereSql = implode(' AND ', $whereClauses);

    return db_all(
        "SELECT *
         FROM customer
         WHERE {$whereSql}
         ORDER BY name ASC",
        $params
    );
}

/* =========================
   JOIN GROUP
========================= */

function get_customers_with_group(): array
{
    return db_all(
        "SELECT c.*,
                g.name AS group_name
         FROM customer c
         LEFT JOIN customer_group g ON g.id = c.group_id
         ORDER BY c.id DESC"
    );
}

function get_customer_detail(int $id): ?array
{
    return db_one(
        "SELECT c.*,
                g.name AS group_name
         FROM customer c
         LEFT JOIN customer_group g ON g.id = c.group_id
         WHERE c.id = :id
         LIMIT 1",
        ['id' => $id]
    );
}