<?php

class ProductModel
{
    protected string $table = 'products';
    protected string $alias = 'p';

    /**
     * WHERE BUILDER (CHỈ BUILD WHERE, KHÔNG FROM)
     */
    private function buildWhere(array $conditions, array &$params): string
    {
        $sql = " WHERE 1=1 ";

        // STATUS
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $sql .= " AND {$this->alias}.status = :status";
            $params['status'] = $conditions['status'];
        }

        // CATEGORY
        if (isset($conditions['category_id']) && $conditions['category_id'] !== '') {
            $sql .= " AND {$this->alias}.category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        // KEYWORD
        if (!empty($conditions['keyword'])) {
            $sql .= " AND {$this->alias}.name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        return $sql;
    }

    /**
     * GET LIST
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $params = [];

        $sql = "
            SELECT 
                {$this->alias}.*,
                c.name AS category_name
            FROM {$this->table} {$this->alias}
            LEFT JOIN categories c 
                ON c.id = {$this->alias}.category_id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= " ORDER BY {$this->alias}.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return Database::get($sql, $params);
    }

    public function getStock(array $conditions = []): array
    {
        $params = [];

        $sql = "
            SELECT
                {$this->alias}.*,

                c.name AS category_name,

                (
                    SELECT pi2.id
                    FROM purchase_items pi2
                    WHERE pi2.product_id = {$this->alias}.id
                    ORDER BY pi2.id DESC
                    LIMIT 1
                ) AS purchase_item_id,

                COALESCE(SUM(pi.quantity), 0) AS stock_in,
                COALESCE(SUM(oi.quantity), 0) AS stock_out,

                (
                    COALESCE(SUM(pi.quantity), 0)
                    - COALESCE(SUM(oi.quantity), 0)
                ) AS stock

            FROM {$this->table} {$this->alias}

            LEFT JOIN categories c
                ON c.id = {$this->alias}.category_id

            LEFT JOIN purchase_items pi
                ON pi.product_id = {$this->alias}.id

            LEFT JOIN order_items oi
                ON oi.product_id = {$this->alias}.id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= "
            GROUP BY {$this->alias}.id

            HAVING (
                COALESCE(SUM(pi.quantity), 0)
                - COALESCE(SUM(oi.quantity), 0)
            ) > 0

            ORDER BY {$this->alias}.id DESC
        ";

        return Database::get($sql, $params);
    }

    /**
     * COUNT
     */
    public function count(array $conditions = []): int
    {
        $params = [];

        $sql = "
            SELECT COUNT(*) as total
            FROM {$this->table} {$this->alias}
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * FIND BY ID
     */
    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * CREATE
     */
    public function create(array $data): int
    {
        $fields = array_keys($data);

        $columns = implode(',', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        return Database::insert($sql, $data);
    }

    /**
     * UPDATE
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";

        return Database::update($sql, $data);
    }

    /**
     * DELETE
     */
    public function deleteById(int $id): int
    {
        return Database::delete(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }
}