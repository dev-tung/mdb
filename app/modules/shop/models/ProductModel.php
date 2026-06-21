<?php

class ProductModel
{
    protected string $table = 'products';

    /**
     * Lấy danh sách sản phẩm theo điều kiện
     *
     * @param array $conditions [
     *      'status' => int|null,
     *      'category_id' => int|null,
     *      'keyword' => string|null
     * ]
     * @param int $limit
     * @param int $offset
     *
     * @return array Danh sách sản phẩm
     *
     * SAMPLE RETURN:
     * [
     *   [
     *     "id" => 1,
     *     "name" => "Yonex Astrox 100ZZ",
     *     "category_id" => 2,
     *     "price" => 4500000,
     *     "sale_price" => 4200000,
     *     "description" => "Vợt cao cấp",
     *     "status" => 1,
     *     "created_at" => "2026-06-21 10:00:00",
     *     "updated_at" => "2026-06-21 10:00:00"
     *   ],
     *   [
     *     "id" => 2,
     *     "name" => "Yonex BG80 String",
     *     "category_id" => 3,
     *     "price" => 120000,
     *     "sale_price" => 100000,
     *     "description" => "Cước căng vợt",
     *     "status" => 1,
     *     "created_at" => "2026-06-21 10:05:00",
     *     "updated_at" => "2026-06-21 10:05:00"
     *   ]
     * ]
     */
    public function getAll(array $conditions = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['category_id'])) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        if (!empty($conditions['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        $sql .= " ORDER BY id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";

            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }

        return Database::get($sql, $params);
    }

    /**
     * Lấy 1 sản phẩm theo ID
     *
     * @param int $id
     * @return array|null
     *
     * SAMPLE RETURN:
     * [
     *   "id" => 1,
     *   "name" => "Yonex Astrox 100ZZ",
     *   "category_id" => 2,
     *   "price" => 4500000,
     *   "sale_price" => 4200000,
     *   "description" => "Vợt cao cấp",
     *   "status" => 1,
     *   "created_at" => "2026-06-21 10:00:00",
     *   "updated_at" => "2026-06-21 10:00:00"
     * ]
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return Database::first($sql, ['id' => $id]);
    }

    /**
     * Lấy sản phẩm theo slug
     *
     * @param string $slug
     * @return array|null
     *
     * SAMPLE RETURN:
     * [
     *   "id" => 1,
     *   "name" => "Yonex Astrox 100ZZ",
     *   "slug" => "yonex-astrox-100zz",
     *   "price" => 4500000
     * ]
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        return Database::first($sql, ['slug' => $slug]);
    }

    /**
     * Thêm sản phẩm mới
     *
     * @param array $data
     * @return int ID sản phẩm vừa tạo
     *
     * SAMPLE INPUT:
     * [
     *   "name" => "Yonex Astrox 100ZZ",
     *   "category_id" => 2,
     *   "price" => 4500000,
     *   "sale_price" => 4200000,
     *   "description" => "Vợt cao cấp",
     *   "status" => 1
     * ]
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
     * Cập nhật sản phẩm theo ID
     *
     * @param int $id
     * @param array $data
     *
     * SAMPLE INPUT:
     * [
     *   "name" => "Yonex Astrox 88D",
     *   "price" => 5000000
     * ]
     */
    public function updateById(int $id, array $data): int
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(',', $set) . " WHERE id = :id";

        $data['id'] = $id;

        return Database::update($sql, $data);
    }

    /**
     * Xoá sản phẩm theo ID
     *
     * @param int $id
     * @return int
     */
    public function deleteById(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return Database::delete($sql, ['id' => $id]);
    }

    /**
     * Đếm sản phẩm theo điều kiện
     *
     * @param array $conditions
     * @return int
     *
     * SAMPLE RETURN:
     * 25
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($conditions['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $conditions['status'];
        }

        if (!empty($conditions['category_id'])) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        $row = Database::first($sql, $params);

        return (int) ($row['total'] ?? 0);
    }
}