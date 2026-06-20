<?php

function create_import(array $input): int
{
    DB::beginTransaction();

    try {

        $now = date('Y-m-d H:i:s');

        $importId = DB::create("shop_import", [
            "supplier_id"    => $input["supplier_id"],
            "description"    => $input["description"] ?? "",
            "status"         => $input["status"],
            "payment_status" => $input["payment_status"],
            "created_at"     => $now,
            "updated_at"     => $now
        ]);

        foreach ($input['product'] as $item) {

            $isGift = !empty($item['is_gift']) && $item['is_gift'] !== "0";

            DB::create("shop_import_product", [
                "import_id"  => $importId,
                "product_id" => $item['id'],
                "quantity"   => $item['quantity'],
                "price"      => $isGift ? 0 : $item['price'],
                "discount"   => $isGift ? 0 : $item['discount'],
                "is_gift"    => $isGift ? 1 : 0,
                "created_at" => $now,
                "updated_at" => $now
            ]);
        }

        DB::commit();

        return $importId;

    } catch (Throwable $e) {

        DB::rollback();

        throw $e;
    }
}

function update_import(int $importId, array $input): void
{
    DB::beginTransaction();

    try {

        $now = date('Y-m-d H:i:s');

        DB::update('shop_import', [
            'supplier_id'    => $input['supplier_id'],
            'description'    => $input['description'] ?? '',
            'status'         => $input['status'],
            'payment_status' => $input['payment_status'],
            'updated_at'     => $now
        ], [
            'id' => $importId
        ]);

        DB::delete('shop_import_product', [
            'import_id' => $importId
        ]);

        foreach ($input['product'] as $item) {

            $isGift = !empty($item['is_gift']) && $item['is_gift'] !== '0';

            DB::create('shop_import_product', [
                'import_id'  => $importId,
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $isGift ? 0 : $item['price'],
                'discount'   => $isGift ? 0 : $item['discount'],
                'is_gift'    => $isGift ? 1 : 0,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        DB::commit();

    } catch (Throwable $e) {

        DB::rollback();

        throw $e;
    }
}

function get_imports(): array
{
    return DB::all(
        "SELECT 
            e.id,
            e.supplier_id,
            s.name AS supplier_name,
            s.address AS supplier_address,
            e.description,
            e.status,
            e.payment_status,
            e.created_at,
            e.updated_at,
            IFNULL(SUM((ep.price - ep.discount) * ep.quantity), 0) AS total_amount,
            IFNULL(SUM(ep.quantity), 0) AS total_quantity
        FROM shop_import e
        LEFT JOIN shop_supplier s ON e.supplier_id = s.id
        LEFT JOIN shop_import_product ep ON e.id = ep.import_id
        GROUP BY e.id
        ORDER BY e.created_at DESC"
    );
}
