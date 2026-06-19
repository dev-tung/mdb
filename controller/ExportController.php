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
}