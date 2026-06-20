<?php

require_once PATH_SHOP . 'repository/supplier.php';

class SupplierController
{
    /* =========================
       LIST SUPPLIERS
    ========================= */

    public function list(): void
    {
        $suppliers = get_suppliers();

        response_success([
            'data' => $suppliers
        ]);
    }

    /* =========================
       SHOW SUPPLIER DETAIL
    ========================= */

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            response_error('Invalid supplier id', 400);
        }

        $supplier = get_supplier_detail($id);

        if (!$supplier) {
            response_error('Supplier not found', 404);
        }

        response_success([
            'data' => $supplier
        ]);
    }
}