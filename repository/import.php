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
                "import_id"        => $importId,
                "product_id"       => $item['id'],
                "quantity"         => $item['quantity'],
                "price"            => $isGift ? 0 : $item['price'],
                "discount"         => $isGift ? 0 : $item['discount'],
                "is_gift"          => $isGift ? 1 : 0,
                "created_at"       => $now,
                "updated_at"       => $now
            ]);
        }

        DB::commit();

        return $importId;

    } catch (Throwable $e) {

        DB::rollback();
        throw $e;
    }
}