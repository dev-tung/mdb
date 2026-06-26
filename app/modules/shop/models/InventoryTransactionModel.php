<?php

class InventoryTransactionModel
{

    /**
     * Tạo lịch sử biến động kho
     */
    public function create(array $data)
    {
        return DB::insert("
            INSERT INTO inventory_transactions
            (
                product_id,type,quantity,
                reference_type,reference_id,note
            )
            VALUES (?,?,?,?,?,?)
        ",[
            $data['product_id'],
            $data['type'],
            $data['quantity'],
            $data['reference_type'] ?? null,
            $data['reference_id'] ?? null,
            $data['note'] ?? null
        ]);
    }


    /**
     * Lấy lịch sử kho
     */
    public function getList(array $filters = [])
    {
        $sql = "
            SELECT it.*,p.name AS product_name
            FROM inventory_transactions it
            JOIN products p ON p.id = it.product_id
            WHERE 1=1
        ";

        $params = [];


        if(!empty($filters['product_id'])){
            $sql .= " AND it.product_id = ?";
            $params[] = $filters['product_id'];
        }


        if(!empty($filters['type'])){
            $sql .= " AND it.type = ?";
            $params[] = $filters['type'];
        }


        $sql .= " ORDER BY it.id DESC";


        return DB::select($sql,$params);
    }


    /**
     * Lấy giao dịch theo chứng từ
     */
    public function getByReference(string $type,int $id)
    {
        return DB::select("
            SELECT *
            FROM inventory_transactions
            WHERE reference_type = ?
            AND reference_id = ?
        ",[
            $type,
            $id
        ]);
    }

}