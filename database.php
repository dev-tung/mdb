<?php

class Database
{
    private static ?PDO $instance = null;

    public static function conn(): PDO
    {
        if (self::$instance === null) {

            $host = 'mysql';
            $db   = 'mdb';
            $user = 'root';
            $pass = 'root';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return self::$instance;
    }
}

/* ======================================================
   QUERY HELPERS
====================================================== */

/**
 * SELECT nhiều dòng
 */
function db_all(string $sql, array $params = []): array
{
    $stmt = Database::conn()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * SELECT 1 dòng
 */
function db_one(string $sql, array $params = []): ?array
{
    $stmt = Database::conn()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * INSERT
 */
function db_insert(string $table, array $data): int
{
    $fields = array_keys($data);
    $columns = implode(',', $fields);
    $placeholders = ':' . implode(', :', $fields);

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    $stmt = Database::conn()->prepare($sql);
    $stmt->execute($data);

    return (int)Database::conn()->lastInsertId();
}

/**
 * UPDATE
 */
function db_update(string $table, array $data, string $where, array $params = []): bool
{
    $set = [];

    foreach ($data as $key => $value) {
        $set[] = "$key = :$key";
    }

    $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $where";

    $stmt = Database::conn()->prepare($sql);

    return $stmt->execute(array_merge($data, $params));
}

/**
 * DELETE
 */
function db_delete(string $table, string $where, array $params = []): bool
{
    $sql = "DELETE FROM $table WHERE $where";

    $stmt = Database::conn()->prepare($sql);
    return $stmt->execute($params);
}

/**
 * COUNT
 */
function db_count(string $table, string $where = "1", array $params = []): int
{
    $sql = "SELECT COUNT(*) as total FROM $table WHERE $where";

    $row = db_one($sql, $params);

    return (int)($row['total'] ?? 0);
}

/**
 * RAW QUERY
 */
function db_query(string $sql, array $params = []): bool
{
    $stmt = Database::conn()->prepare($sql);
    return $stmt->execute($params);
}