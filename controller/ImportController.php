<?php
require_once PATH_VALIDATE . 'import.php';
require_once PATH_REPOSITORY . 'import.php';

class ImportController extends BaseController
{
    public function create()
    {
        $input = request_input();

        $errors = validate_import($input);

        if (import_validate_fails($errors)) {
            return json_response([
                'success' => false,
                'errors' => $errors
            ]);
        }

        try {

            create_import($input);

            return json_response([
                'success' => true,
                'message' => 'Tạo đơn nhập hàng thành công.'
            ]);

        } catch (Throwable $e) {

            return json_response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}