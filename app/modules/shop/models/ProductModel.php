<?php

class ProductModel
{
    protected string $table = 'products';

    /**
     * Lấy danh sách sản phẩm (có filter + pagination)
     */
    public function getList(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // STATUS (fix lỗi status = 0)
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        // CATEGORY
        if (isset($conditions['category_id']) && $conditions['category_id'] !== '') {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        // KEYWORD
        if (!empty($conditions['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $sql .= " ORDER BY id DESC";

        // PAGINATION
        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::get($sql, $params);
    }

    /**
     * Lấy 1 sản phẩm theo ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return Database::first($sql, ['id' => $id]);
    }

    /**
     * Lấy sản phẩm theo slug
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        return Database::first($sql, ['slug' => $slug]);
    }

    /**
     * Thêm sản phẩm
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
     * Update theo ID
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";

        $data['id'] = $id;

        return Database::update($sql, $data);
    }

    /**
     * Xoá theo ID
     */
    public function deleteById(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return Database::delete($sql, ['id' => $id]);
    }

    /**
     * Đếm tổng (phục vụ pagination)
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        // STATUS
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        // CATEGORY
        if (isset($conditions['category_id']) && $conditions['category_id'] !== '') {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        // KEYWORD (nếu muốn đồng bộ với list)
        if (!empty($conditions['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}