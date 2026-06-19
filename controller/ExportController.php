<?php
require_once PATH_VALIDATE . 'export.php';
require_once PATH_REPOSITORY . 'export.php';

class ExportController extends BaseController
{
    public function product(): void
    {
        $products = get_stock_products();
        $this->success($products);
    }

    public function list()
    {
        $products = get_exports();
        $this->success($products);
    }

    public function create()
    {
        $input = request_input();

        $errors = validate_export($input);

        if (export_validate_fails($errors)) {
            return json_response([
                'success' => false,
                'errors' => $errors
            ]);
        }

        try {

            create_export($input);

            return json_response([
                'success' => true,
                'message' => 'Tạo đơn hàng thành công.'
            ]);

        } catch (Throwable $e) {

            return json_response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show()
    {
        $input = request_input();

        $id = $input['id'] ?? null;

        if (!is_numeric($id) || $id <= 0) {
            return json_response([
                "success" => false,
                "message" => "ID export không hợp lệ.",
                "data"    => []
            ]);
        }

        try {

            // Lấy export + customer
            $export = DB::row("
                SELECT 
                    e.id,
                    e.customer_id,
                    c.name AS customer_name,
                    c.phone AS customer_phone,
                    c.address AS customer_address,
                    e.description,
                    e.status,
                    e.payment_status
                FROM shop_export e
                LEFT JOIN customer c ON c.id = e.customer_id
                WHERE e.id = ?
                LIMIT 1
            ", [$id]);

            if (!$export) {
                return json_response([
                    "success" => false,
                    "message" => "Không tìm thấy export.",
                    "data"    => []
                ]);
            }

            // ------------------------------------
            // Lấy danh sách sản phẩm (có import_product_id)
            // ------------------------------------
            $products = DB::query("
                SELECT 
                    ep.product_id,
                    p.name AS product_name,
                    ep.quantity,
                    ep.price,
                    ep.discount,
                    ep.import_product_id  -- ✅ giữ import_product_id
                FROM shop_export_product ep
                LEFT JOIN shop_product p ON p.id = ep.product_id
                WHERE ep.export_id = ?
                ORDER BY ep.id ASC
            ", [$id])->fetchAll();


            // Gắn vào row
            $export['products'] = $products;

            // Trả về ARRAY để frontend dùng json.data[0]
            return json_response([
                "success" => true,
                "data"    => [$export]
            ]);

        } catch (\Exception $e) {

            return json_response([
                "success" => false,
                "message" => "Có lỗi xảy ra khi lấy dữ liệu export.",
                "data"    => []
            ]);
        }
    }

    // -----------------------------
    // API: UPDATE EXPORT STATUS
    // -----------------------------
    public function status()
    {
        $input = request_input();
        if (empty($input['id']) || empty($input['status'])) {
            return json_response([
                "success" => false,
                "message" => "ID hoặc trạng thái không hợp lệ."
            ]);
        }

        try {
            DB::update("shop_export", [
                "status"      => $input['status'],
                "updated_at"  => date('Y-m-d H:i:s')
            ], ["id" => $input['id']]);

            return json_response([
                "success" => true,
                "message" => "Cập nhật trạng thái thành công."
            ]);
        } catch (\Exception $e) {
            return json_response([
                "success" => false,
                "message" => "Lỗi khi cập nhật trạng thái: " . $e->getMessage()
            ]);
        }
    }

    // -----------------------------
    // API: UPDATE PAYMENT STATUS
    // -----------------------------
    public function payment()
    {
        $input = request_input();
        if (empty($input['id']) || empty($input['payment_status'])) {
            return json_response([
                "success" => false,
                "message" => "ID hoặc trạng thái thanh toán không hợp lệ."
            ]);
        }

        try {
            DB::update("shop_export", [
                "payment_status" => $input['payment_status'],
                "updated_at"     => date('Y-m-d H:i:s')
            ], ["id" => $input['id']]);

            return json_response([
                "success" => true,
                "message" => "Cập nhật trạng thái thanh toán thành công."
            ]);
        } catch (\Exception $e) {
            return json_response([
                "success" => false,
                "message" => "Lỗi khi cập nhật trạng thái thanh toán: " . $e->getMessage()
            ]);
        }
    }

    // -----------------------------
    // API: DELETE EXPORT
    // -----------------------------
    public function delete()
    {
        $input = request_input();
        if (empty($input['id'])) {
            return json_response([
                "success" => false,
                "message" => "ID export không hợp lệ."
            ]);
        }

        try {
            // Xóa chi tiết sản phẩm trước
            DB::delete("shop_export_product", ["export_id" => (int)$input['id']]);
            // Xóa export chính
            DB::delete("shop_export", ["id" => (int)$input['id']]);

            return json_response([
                "success" => true,
                "message" => "Xóa đơn hàng thành công."
            ]);
        } catch (\Exception $e) {
            return json_response([
                "success" => false,
                "message" => "Lỗi khi xóa export: " . $e->getMessage()
            ]);
        }
    }
}