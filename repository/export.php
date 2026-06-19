<?php

function get_stock_products(): array
{
    return DB::all(
        "SELECT
            ip.id AS import_product_id,
            p.id AS id,
            p.name,
            p.sale_price AS price,
            i.created_at AS import_date,

            DATEDIFF(CURDATE(), DATE(i.created_at)) AS days_in_stock,

            (ip.quantity - IFNULL(SUM(ep.quantity), 0)) AS quantity

        FROM shop_import_product ip

        JOIN shop_import i
            ON i.id = ip.import_id
           AND i.status = 'completed'

        JOIN shop_product p
            ON p.id = ip.product_id

        LEFT JOIN shop_export_product ep
            ON ep.import_product_id = ip.id

        GROUP BY
            ip.id,
            p.id,
            p.name,
            p.sale_price,
            i.created_at,
            ip.quantity

        HAVING (ip.quantity - IFNULL(SUM(ep.quantity), 0)) > 0

        ORDER BY
            p.id,
            i.created_at,
            ip.id"
    );
}

function create_export(array $input): int
{
    DB::beginTransaction();

    try {

        $now = date('Y-m-d H:i:s');

        $exportId = DB::create("shop_export", [
            "customer_id"    => $input["customer_id"],
            "description"    => $input["description"] ?? "",
            "status"         => $input["status"],
            "payment_status" => $input["payment_status"],
            "created_at"     => $now,
            "updated_at"     => $now
        ]);

        foreach ($input['product'] as $item) {

            $isGift = !empty($item['is_gift']) && $item['is_gift'] !== "0";

            DB::create("shop_export_product", [
                "export_id"        => $exportId,
                "product_id"       => $item['id'],
                "quantity"         => $item['quantity'],
                "price"            => $isGift ? 0 : $item['price'],
                "discount"         => $isGift ? 0 : $item['discount'],
                "is_gift"          => $isGift ? 1 : 0,
                "import_product_id"=> $item['import_product_id'] ?? null,
                "created_at"       => $now,
                "updated_at"       => $now
            ]);
        }

        DB::commit();

        return $exportId;

    } catch (Throwable $e) {

        DB::rollback();
        throw $e;
    }
}