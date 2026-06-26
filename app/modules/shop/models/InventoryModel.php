<?php

class InventoryModel
{

    /**
     * Lấy toàn bộ sản phẩm kèm tồn kho
     * Bao gồm sản phẩm chưa có tồn
     */
    public function getList(array $filters = [])
    {
        $sql = "
            SELECT
                p.*,
                COALESCE(i.quantity,0) AS stock

            FROM products p

            LEFT JOIN inventories i
            ON i.product_id = p.id

            WHERE 1=1
        ";

        $params = [];


        if (!empty($filters['keyword'])) {

            $sql .= " AND p.name LIKE ? ";

            $params[] = '%' . $filters['keyword'] . '%';

        }


        if (!empty($filters['category_id'])) {

            $sql .= " AND p.category_id = ? ";

            $params[] = $filters['category_id'];

        }


        $sql .= " ORDER BY p.id DESC ";


        return DB::select($sql, $params);

    }



    /**
     * Lấy sản phẩm đang còn tồn kho
     * Chỉ lấy stock > 0
     */
    public function getStock(array $filters = [])
    {
        $sql = "
            SELECT
                p.*,
                i.quantity AS stock

            FROM products p

            INNER JOIN inventories i
            ON i.product_id = p.id

            WHERE i.quantity > 0
        ";

        $params = [];


        if (!empty($filters['keyword'])) {

            $sql .= " AND p.name LIKE ? ";

            $params[] = '%' . $filters['keyword'] . '%';

        }


        if (!empty($filters['category_id'])) {

            $sql .= " AND p.category_id = ? ";

            $params[] = $filters['category_id'];

        }


        $sql .= " ORDER BY p.id DESC ";


        return DB::select($sql, $params);

    }



    /**
     * Tăng tồn kho khi nhập hàng
     */
    public function increase(int $productId, int $quantity)
    {
        return DB::execute("

            INSERT INTO inventories
            (
                product_id,
                quantity
            )

            VALUES (?,?)

            ON DUPLICATE KEY UPDATE

            quantity = quantity + VALUES(quantity)

        ",[
            $productId,
            $quantity
        ]);
    }



    /**
     * Giảm tồn kho khi bán hàng
     */
    public function decrease(int $productId, int $quantity)
    {
        return DB::execute("

            UPDATE inventories

            SET quantity = quantity - ?

            WHERE product_id = ?

        ",[
            $quantity,
            $productId
        ]);
    }

}