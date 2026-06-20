<?php

require_once PATH_SHOP . 'repository/import.php';
require_once PATH_SHOP . 'validate/import.php';

class ImportController
{
    public function list(): void
    {
        response_success([
            'data' => get_imports()
        ]);
    }

    public function create(): void
    {
        $input = request_input();

        $errors = validate_import($input);

        if (import_validate_fails($errors)) {
            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            create_import($input);

            response_success(
                [],
                'Tạo đơn hàng thành công.'
            );

        } catch (Throwable $e) {

            response_error($e->getMessage());
        }
    }

    public function update(): void
    {
        $input = request_input();

        $id = $input['id'] ?? 0;

        $errors = validate_import($input);

        if (import_validate_fails($errors)) {

            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            update_import($id, $input);

            response_success(
                [],
                'Cập nhật đơn hàng thành công.'
            );

        } catch (Throwable $e) {

            response_error($e->getMessage());
        }
    }

    public function show(): void
    {
        $input = request_input();

        $id = $input['id'] ?? 0;

        if (!is_numeric($id) || $id <= 0) {
            response_error(
                'ID import không hợp lệ.',
                400,
                ['data' => []]
            );
        }

        try {

            $import = DB::row("
                SELECT 
                    i.id,
                    i.supplier_id,
                    s.name AS supplier_name,
                    i.description,
                    i.status,
                    i.payment_status,
                    i.created_at,
                    i.updated_at
                FROM shop_import i
                LEFT JOIN shop_supplier s ON s.id = i.supplier_id
                WHERE i.id = ?
                LIMIT 1
            ", [$id]);

            if (!$import) {
                response_error(
                    'Không tìm thấy import.',
                    404,
                    ['data' => []]
                );
            }

            $products = DB::query("
                SELECT
                    ip.product_id,
                    p.name AS product_name,
                    ip.quantity,
                    ip.price,
                    ip.discount,
                    ip.is_gift
                FROM shop_import_product ip
                LEFT JOIN shop_product p ON p.id = ip.product_id
                WHERE ip.import_id = ?
                ORDER BY ip.id ASC
            ", [$id])->fetchAll();

            $import['products'] = $products;

            response_success([
                'data' => [$import]
            ]);

        } catch (Throwable $e) {

            response_error(
                'Có lỗi xảy ra khi lấy dữ liệu import.'
            );
        }
    }

    public function status(): void
    {
        $input = request_input();

        if (empty($input['id']) || empty($input['status'])) {
            response_error(
                'ID hoặc trạng thái không hợp lệ.'
            );
        }

        try {

            DB::update('shop_import', [
                'status' => $input['status'],
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'id' => $input['id']
            ]);

            response_success(
                [],
                'Cập nhật trạng thái thành công.'
            );

        } catch (Throwable $e) {

            response_error(
                'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            );
        }
    }

    public function payment(): void
    {
        $input = request_input();

        if (
            empty($input['id']) ||
            empty($input['payment_status'])
        ) {
            response_error(
                'ID hoặc trạng thái thanh toán không hợp lệ.'
            );
        }

        try {

            DB::update('shop_import', [
                'payment_status' => $input['payment_status'],
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'id' => $input['id']
            ]);

            response_success(
                [],
                'Cập nhật trạng thái thanh toán thành công.'
            );

        } catch (Throwable $e) {

            response_error(
                'Lỗi khi cập nhật trạng thái thanh toán: ' . $e->getMessage()
            );
        }
    }

    public function delete(): void
    {
        $input = request_input();

        if (empty($input['id'])) {
            response_error(
                'ID import không hợp lệ.'
            );
        }

        try {

            DB::delete(
                'shop_import_product',
                ['import_id' => (int)$input['id']]
            );

            DB::delete(
                'shop_import',
                ['id' => (int)$input['id']]
            );

            response_success(
                [],
                'Xóa đơn hàng thành công.'
            );

        } catch (Throwable $e) {

            response_error(
                'Lỗi khi xóa import: ' . $e->getMessage()
            );
        }
    }
}