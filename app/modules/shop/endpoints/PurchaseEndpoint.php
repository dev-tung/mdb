<?php

class PurchaseEndpoint
{
    protected PurchaseModel $purchaseModel;
    protected PurchaseItemModel $purchaseItemModel;

    public function __construct()
    {
        $this->purchaseModel = new PurchaseModel();
        $this->purchaseItemModel = new PurchaseItemModel();
    }

    // =========================
    // LIST
    // =========================
    public function apiList()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters(['keyword', 'supplier_id', 'status']);

        $data = $this->purchaseModel->getList(
            $filters,
            $limit,
            ($page - 1) * $limit
        );

        $total = $this->purchaseModel->count($filters);

        return Response::json([
            'data' => $data,
            'meta' => [
                'page'       => $page,
                'total'      => $total,
                'totalPages' => ceil($total / $limit),
                'perPage'    => $limit
            ]
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function apiShow($id)
    {
        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $purchase = $this->purchaseModel->findById($id);

        if (!$purchase) {
            return Response::json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu nhập'
            ]);
        }

        $items = $this->purchaseItemModel->getByPurchaseId($id);

        return Response::json([
            'success' => true,
            'data' => [
                'purchase' => $purchase,
                'items'    => $items
            ]
        ]);
    }

    // =========================
    // CREATE
    // =========================
    public function apiCreate()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $supplier_id  = (int)($input['supplier_id'] ?? 0);
        $warehouse_id = (int)($input['warehouse_id'] ?? 0);
        $status       = $input['status'] ?? 'draft';
        $items        = $input['products'] ?? [];

        if ($supplier_id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'Nhà cung cấp không hợp lệ'
            ]);
        }

        if (empty($items)) {
            return Response::json([
                'success' => false,
                'message' => 'Chưa có sản phẩm'
            ]);
        }

        // =========================
        // CREATE PURCHASE
        // =========================
        $purchaseId = $this->purchaseModel->create([
            'supplier_id'  => $supplier_id,
            'warehouse_id' => $warehouse_id,
            'status'       => $status,
            'total_cost'   => 0
        ]);

        $total = 0;

        // =========================
        // CREATE ITEMS
        // =========================
        foreach ($items as $item) {

            $product_id = (int)($item['id'] ?? 0);
            $qty        = (int)($item['quantity'] ?? 1);
            $price      = (float)($item['price'] ?? 0);

            if ($product_id <= 0 || $qty <= 0) {
                continue;
            }

            $lineTotal = $qty * $price;
            $total += $lineTotal;

            $this->purchaseItemModel->create([
                'purchase_id' => $purchaseId,
                'product_id'  => $product_id,
                'quantity'    => $qty,
                'unit_price'  => $price
            ]);
        }

        // update total
        $this->purchaseModel->updateById($purchaseId, [
            'total_cost' => $total
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Tạo phiếu nhập thành công',
            'id'      => $purchaseId
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function apiUpdate()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $updated = $this->purchaseModel->updateById($id, [
            'supplier_id'  => (int)($input['supplier_id'] ?? 0),
            'warehouse_id' => (int)($input['warehouse_id'] ?? 0),
            'status'       => $input['status'] ?? 'draft'
        ]);

        return Response::json([
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Cập nhật thành công' : 'Không có thay đổi'
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function apiDelete()
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        // delete items first (safe)
        $this->purchaseItemModel->deleteByPurchaseId($id);

        $deleted = $this->purchaseModel->deleteById($id);

        return Response::json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy phiếu nhập'
        ]);
    }
}