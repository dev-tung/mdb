<?php
require_once PATH_REPOSITORY . 'supplier.php';

class SupplierController extends BaseController
{
    /* =========================
       LIST SUPPLIERS
    ========================= */

    public function list(): void
    {
        $suppliers = get_suppliers();

        $this->success([
            'data' => $suppliers
        ]);
    }

    /* =========================
       SHOW SUPPLIER DETAIL
    ========================= */

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->error('Invalid supplier id', 400);
        }

        $supplier = get_supplier_detail($id);

        if (!$supplier) {
            $this->error('Supplier not found', 404);
        }

        $this->success($supplier);
    }

    /* =========================
       SEARCH SUPPLIER (FOR ORDER)
    ========================= */

    public function search(): void
    {
        $keyword = $_GET['keyword'] ?? '';

        $suppliers = search_suppliers($keyword);

        $this->success([
            'data' => $suppliers
        ]);
    }
}