<?php
require_once PATH_ROOT . 'validate/export.php';
require_once PATH_ROOT . 'repository/export.php';

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

        $validator = new ExportValidator($input);

        if ($validator->fails()) {
            return json_response([
                'success' => false,
                'errors' => $validator->errors()
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