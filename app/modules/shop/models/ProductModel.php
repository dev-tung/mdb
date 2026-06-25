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
        if (!empty($conditions['status'])) {
            $sql .= " AND {$this->alias}.status = :status";
            $params['status'] = $conditions['status'];
        }

        // CATEGORY
        if (!empty($conditions['category_id'])) {
            $sql .= " AND {$this->alias}.category_id = :category_id";
            $params['category_id'] = $conditions['category_id'];
        }

        // KEYWORD
        if (!empty($conditions['keyword'])) {
            $sql .= " AND {$this->alias}.name LIKE :keyword";
            $params['keyword'] = '%' . $conditions['keyword'] . '%';
        }

        // BRANDS
        if (!empty($conditions['brands']) && is_array($conditions['brands'])) {

            $placeholders = [];

            foreach ($conditions['brands'] as $index => $brandId) {

                $key = "brand_" . $index;

                $placeholders[] = ":" . $key;
                $params[$key] = (int)$brandId;
            }

            if (!empty($placeholders)) {
                $sql .= " AND {$this->alias}.brand_id IN (" . implode(',', $placeholders) . ")";
            }
        }

        // PRICE RANGE
        if (!empty($conditions['price'])) {

            $priceRanges = config('shop.option.price_range') ?? [];

            if (isset($priceRanges[$conditions['price']])) {

                $range = $priceRanges[$conditions['price']];

                if ($range['max'] === null) {

                    $sql .= " AND {$this->alias}.price >= :price_min";
                    $params['price_min'] = $range['min'];

                } else {

                    $sql .= " AND {$this->alias}.price BETWEEN :price_min AND :price_max";

                    $params['price_min'] = $range['min'];
                    $params['price_max'] = $range['max'];
                }
            }
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

    public function getAvailable(array $conditions = []): array
    {
        $params = [];

        $sql = "
            SELECT
                {$this->alias}.*,

                c.name AS category_name,

                last_purchase.purchase_item_id,

                COALESCE(stock_in.stock_in, 0) AS stock_in,

                COALESCE(stock_out.stock_out, 0) AS stock_out,

                (
                    COALESCE(stock_in.stock_in, 0)
                    - COALESCE(stock_out.stock_out, 0)
                ) AS stock

            FROM {$this->table} {$this->alias}

            LEFT JOIN categories c
                ON c.id = {$this->alias}.category_id

            LEFT JOIN (
                SELECT
                    pi.product_id,
                    SUM(pi.quantity) AS stock_in
                FROM purchase_items pi
                INNER JOIN purchases p
                    ON p.id = pi.purchase_id
                WHERE p.status = 'received'
                GROUP BY pi.product_id
            ) stock_in
                ON stock_in.product_id = {$this->alias}.id

            LEFT JOIN (
                SELECT
                    oi.product_id,
                    SUM(oi.quantity) AS stock_out
                FROM order_items oi
                INNER JOIN orders o
                    ON o.id = oi.order_id
                WHERE o.status = 'completed'
                GROUP BY oi.product_id
            ) stock_out
                ON stock_out.product_id = {$this->alias}.id

            LEFT JOIN (
                SELECT
                    pi.product_id,
                    MAX(pi.id) AS purchase_item_id
                FROM purchase_items pi
                INNER JOIN purchases p
                    ON p.id = pi.purchase_id
                WHERE p.status = 'received'
                GROUP BY pi.product_id
            ) last_purchase
                ON last_purchase.product_id = {$this->alias}.id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= "
            HAVING (
                COALESCE(stock_in.stock_in, 0)
                - COALESCE(stock_out.stock_out, 0)
            ) > 0

            ORDER BY {$this->alias}.id DESC
        ";

        return Database::get($sql, $params);
    }

    public function getStock(array $conditions = []): array
    {
        $params = [];

        $sql = "
            SELECT
                {$this->alias}.*,

                c.name AS category_name,

                last_purchase.purchase_item_id,

                COALESCE(stock_in.stock_in, 0) AS stock_in,

                COALESCE(stock_out.stock_out, 0) AS stock_out,

                (
                    COALESCE(stock_in.stock_in, 0)
                    - COALESCE(stock_out.stock_out, 0)
                ) AS stock

            FROM {$this->table} {$this->alias}

            LEFT JOIN categories c
                ON c.id = {$this->alias}.category_id

            LEFT JOIN (
                SELECT
                    pi.product_id,
                    SUM(pi.quantity) AS stock_in
                FROM purchase_items pi
                INNER JOIN purchases p
                    ON p.id = pi.purchase_id
                WHERE p.status = 'received'
                GROUP BY pi.product_id
            ) stock_in
                ON stock_in.product_id = {$this->alias}.id

            LEFT JOIN (
                SELECT
                    oi.product_id,
                    SUM(oi.quantity) AS stock_out
                FROM order_items oi
                INNER JOIN orders o
                    ON o.id = oi.order_id
                WHERE o.status = 'completed'
                GROUP BY oi.product_id
            ) stock_out
                ON stock_out.product_id = {$this->alias}.id

            LEFT JOIN (
                SELECT
                    pi.product_id,
                    MAX(pi.id) AS purchase_item_id
                FROM purchase_items pi
                INNER JOIN purchases p
                    ON p.id = pi.purchase_id
                WHERE p.status = 'received'
                GROUP BY pi.product_id
            ) last_purchase
                ON last_purchase.product_id = {$this->alias}.id
        ";

        $sql .= $this->buildWhere($conditions, $params);

        $sql .= "
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

    public function getRackets(int $limit = 6): array
    {
        $sql = "
            SELECT 
                p.*,
                c.name AS category_name
            FROM {$this->table} {$this->alias}
            LEFT JOIN categories c 
                ON c.id = {$this->alias}.category_id
            WHERE p.status = 1
            AND p.category_id = 1
            ORDER BY p.id DESC
            LIMIT {$limit}
        ";

        return Database::get($sql);
    }

    public function findBySlug(string $slug): ?array
    {
        $product = Database::first("
            SELECT 
                p.*,
                c.name AS category_name,
                b.name AS brand_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN brands b ON b.id = p.brand_id
            WHERE p.slug = :slug
            LIMIT 1
        ", [
            'slug' => $slug
        ]);

        if (!$product) return null;

        // IMAGES
        $images = Database::get("
            SELECT image 
            FROM product_images
            WHERE product_id = :id
            ORDER BY sort_order ASC, id ASC
        ", [
            'id' => $product['id']
        ]);

        $product['gallery'] = array_column($images, 'image');

        // ATTRIBUTES
        $attributes = Database::get("
            SELECT attribute_name, attribute_value
            FROM product_attributes
            WHERE product_id = :id
            ORDER BY id ASC
        ", [
            'id' => $product['id']
        ]);

        $product['attributes'] = $attributes;

        return $product;
    }
}