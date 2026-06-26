<?php

class InventoryModel
{

    public function apiList(array $conditions = []): array
    {
        $params = [];

        $sql = "
            SELECT
                it.*,
                p.name AS product_name,
                w.name AS warehouse_name
            FROM inventory_transactions it
            LEFT JOIN products p ON p.id = it.product_id
            LEFT JOIN warehouses w ON w.id = it.warehouse_id
        ";

        $sql .= $this->buildWhere($conditions,$params);

        $sql .= " ORDER BY it.id DESC ";

        return Database::get($sql,$params);
    }


    public function apiShow($id): ?array
    {
        return Database::first("
            SELECT
                it.*,
                p.name AS product_name,
                w.name AS warehouse_name
            FROM inventory_transactions it
            LEFT JOIN products p ON p.id = it.product_id
            LEFT JOIN warehouses w ON w.id = it.warehouse_id
            WHERE it.id=:id
            LIMIT 1
        ",[
            'id'=>$id
        ]);
    }


    public function create(array $data): int
    {
        return Database::insert('inventory_transactions',$data);
    }


    public function update(int $id,array $data): bool
    {
        return Database::update(
            'inventory_transactions',
            $data,
            ['id'=>$id]
        );
    }


    public function delete(int $id): bool
    {
        return Database::delete(
            'inventory_transactions',
            ['id'=>$id]
        );
    }


    private function buildWhere(array $conditions,array &$params): string
    {
        $sql=" WHERE 1=1 ";

        if(!empty($conditions['product_id'])){
            $sql.=" AND it.product_id=:product_id";
            $params['product_id']=$conditions['product_id'];
        }

        if(!empty($conditions['warehouse_id'])){
            $sql.=" AND it.warehouse_id=:warehouse_id";
            $params['warehouse_id']=$conditions['warehouse_id'];
        }

        if(!empty($conditions['type'])){
            $sql.=" AND it.type=:type";
            $params['type']=$conditions['type'];
        }

        if(!empty($conditions['reference_type'])){
            $sql.=" AND it.reference_type=:reference_type";
            $params['reference_type']=$conditions['reference_type'];
        }

        if(!empty($conditions['keyword'])){
            $sql.=" AND p.name LIKE :keyword";
            $params['keyword']='%'.$conditions['keyword'].'%';
        }

        return $sql;
    }

}