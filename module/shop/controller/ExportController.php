<?php

require_once PATH_SHOP . 'repository/export.php';
require_once PATH_SHOP . 'validate/export.php';

class ExportController
{
    public function product(): void
    {
        response_success([
            'data' => product_export()
        ]);
    }

    public function list(): void
    {
        response_success([
            'data' => get_exports()
        ]);
    }

    public function create(): void
    {
        $input = request_input();

        $errors = validate_export($input);

        if (export_validate_fails($errors)) {
            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            create_export($input);

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

        $errors = validate_export($input);

        if (export_validate_fails($errors)) {

            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            update_export($id, $input);

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
                'ID export không hợp lệ.',
                400,
                ['data' => []]
            );
        }

        try {

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
                response_error(
                    'Không tìm thấy export.',
                    404,
                    ['data' => []]
                );
            }

            $products = DB::query("
                SELECT
                    ep.product_id,
                    p.name AS product_name,
                    ep.quantity,
                    ep.price,
                    ep.discount,
                    ep.import_product_id
                FROM shop_export_product ep
                LEFT JOIN shop_product p ON p.id = ep.product_id
                WHERE ep.export_id = ?
                ORDER BY ep.id ASC
            ", [$id])->fetchAll();

            $export['products'] = $products;

            response_success([
                'data' => [$export]
            ]);

        } catch (Throwable $e) {

            response_error(
                'Có lỗi xảy ra khi lấy dữ liệu export.'
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

            DB::update('shop_export', [
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

            DB::update('shop_export', [
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
                'ID export không hợp lệ.'
            );
        }

        try {

            DB::delete(
                'shop_export_product',
                ['export_id' => (int)$input['id']]
            );

            DB::delete(
                'shop_export',
                ['id' => (int)$input['id']]
            );

            response_success(
                [],
                'Xóa đơn hàng thành công.'
            );

        } catch (Throwable $e) {

            response_error(
                'Lỗi khi xóa export: ' . $e->getMessage()
            );
        }
    }
}