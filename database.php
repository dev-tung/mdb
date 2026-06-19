<?php

class DB
{
    private static ?PDO $pdo = null;

    // ---------------------------------
    // KẾT NỐI DUY NHẤT (SINGLETON)
    // ---------------------------------
    private static function connect(): PDO
    {
        if (self::$pdo === null) {

            $host = 'mysql';
            $db   = 'badminton';
            $user = 'root';
            $pass = 'root';

            try {

                self::$pdo = new PDO(
                    "mysql:host={$host};dbname={$db};charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $e) {

                die('DB Connection Failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    // ---------------------------------
    // QUERY THƯỜNG
    // ---------------------------------
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    // ---------------------------------
    // LẤY NHIỀU BẢN GHI
    // ---------------------------------
    public static function all(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    // ---------------------------------
    // LẤY 1 BẢN GHI
    // ---------------------------------
    public static function row(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();

        return $result ?: null;
    }

    // ---------------------------------
    // LẤY 1 GIÁ TRỊ
    // ---------------------------------
    public static function value(string $sql, array $params = [])
    {
        $row = self::row($sql, $params);

        if (!$row) {
            return null;
        }

        return reset($row);
    }

    // ---------------------------------
    // GỌI STORE PROCEDURE
    // ---------------------------------
    public static function call(string $store, array $params = []): array
    {
        $placeholder = implode(',', array_fill(0, count($params), '?'));

        $sql = "CALL {$store}({$placeholder})";

        $stmt = self::connect()->prepare($sql);

        $stmt->execute(array_values($params));

        $result = $stmt->fetchAll();

        while ($stmt->nextRowset()) {
        }

        return $result;
    }

    // ---------------------------------
    // CREATE
    // ---------------------------------
    public static function create(string $table, array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException(
                'Dữ liệu insert không được rỗng.'
            );
        }

        $columns = implode(', ', array_keys($data));

        $placeholders = implode(
            ', ',
            array_fill(0, count($data), '?')
        );

        $sql = "
            INSERT INTO `$table`
            ($columns)
            VALUES
            ($placeholders)
        ";

        self::query($sql, array_values($data));

        return (int) self::connect()->lastInsertId();
    }

    // ---------------------------------
    // UPDATE
    // ---------------------------------
    public static function update(
        string $table,
        array $data,
        array $where
    ): bool {
        if (empty($data)) {
            throw new InvalidArgumentException(
                'Dữ liệu update không được rỗng.'
            );
        }

        if (empty($where)) {
            throw new InvalidArgumentException(
                'Điều kiện WHERE không được rỗng.'
            );
        }

        $set = [];
        $values = [];

        foreach ($data as $column => $value) {

            $set[] = "`$column` = ?";
            $values[] = $value;
        }

        $conditions = [];

        foreach ($where as $column => $value) {

            $conditions[] = "`$column` = ?";
            $values[] = $value;
        }

        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE %s",
            $table,
            implode(', ', $set),
            implode(' AND ', $conditions)
        );

        return self::query($sql, $values)->rowCount() > 0;
    }

    // ---------------------------------
    // DELETE
    // ---------------------------------
    public static function delete(
        string $table,
        array $where
    ): bool {
        if (empty($where)) {
            throw new InvalidArgumentException(
                'Điều kiện WHERE không được rỗng.'
            );
        }

        $conditions = [];
        $values = [];

        foreach ($where as $column => $value) {

            $conditions[] = "`$column` = ?";
            $values[] = $value;
        }

        $sql = sprintf(
            "DELETE FROM `%s` WHERE %s",
            $table,
            implode(' AND ', $conditions)
        );

        return self::query($sql, $values)->rowCount() > 0;
    }

    // ---------------------------------
    // LẤY 1 BẢN GHI THEO ID
    // ---------------------------------
    public static function show(
        string $table,
        int $id
    ): ?array {
        return self::row(
            "SELECT * FROM `$table` WHERE id = ? LIMIT 1",
            [$id]
        );
    }

    // ---------------------------------
    // LẤY TOÀN BỘ BẢN GHI
    // ---------------------------------
    public static function get(
        string $table,
        string $orderBy = 'id',
        string $direction = 'DESC'
    ): array {
        $direction =
            strtoupper($direction) === 'ASC'
                ? 'ASC'
                : 'DESC';

        $sql = "
            SELECT *
            FROM `$table`
            ORDER BY `$orderBy` $direction
        ";

        return self::all($sql);
    }

    // ---------------------------------
    // TRANSACTION
    // ---------------------------------
    public static function beginTransaction(): bool
    {
        return self::connect()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::connect()->commit();
    }

    public static function rollback(): bool
    {
        if (self::connect()->inTransaction()) {
            return self::connect()->rollBack();
        }

        return false;
    }

    // ---------------------------------
    // PDO GỐC (KHI CẦN)
    // ---------------------------------
    public static function pdo(): PDO
    {
        return self::connect();
    }
}