<?php

function get_stock_products(): ?array
{
    return db_all(
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

      ORDER BY p.id, i.created_at, ip.id"
    );
}