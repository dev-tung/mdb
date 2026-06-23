<?php

class OrderModel
{
    protected string $table = 'orders';

    // =========================
    // LIST
    // =========================
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT 
                    o.*,
                    c.name AS customer_name
                FROM {$this->table} o
                LEFT JOIN customers c ON c.id = o.customer_id
                WHERE 1=1";

        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND o.id LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['customer_id'])) {
            $sql .= " AND o.customer_id = :customer_id";
            $params['customer_id'] = $conditions['customer_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND o.status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND o.payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        $sql .= " ORDER BY o.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    // =========================
    // FIND BY ID
    // =========================
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT o.*, c.name AS customer_name
            FROM {$this->table} o
            LEFT JOIN customers c ON c.id = o.customer_id
            WHERE o.id = :id
            LIMIT 1",
            ['id' => $id]
        );
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns})
                VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    // =========================
    // UPDATE
    // =========================
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table}
                SET " . implode(', ', $set) . "
                WHERE id = :id";

        return Database::update($sql, $data);
    }

    // =========================
    // DELETE
    // =========================
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    // =========================
    // COUNT
    // =========================
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['keyword'])) {
            $sql .= " AND id LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        if (!empty($conditions['customer_id'])) {
            $sql .= " AND customer_id = :customer_id";
            $params['customer_id'] = $conditions['customer_id'];
        }

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['payment'])) {
            $sql .= " AND payment = :payment";
            $params['payment'] = $conditions['payment'];
        }

        $row = Database::first($sql, $params);

        return (int)($row['total'] ?? 0);
    }
}