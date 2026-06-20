<?php

require_once PATH_SHOP . 'repository/import.php';
require_once PATH_SHOP . 'validate/import.php';

class ImportController
{
    public function create(): void
    {
        $input = request_input();

        $errors = validate_import($input);

        if (import_validate_fails($errors)) {
            response_error(
                'Dữ liệu không hợp lệ',
                422,
                [
                    'errors' => $errors
                ]
            );
        }

        try {

            create_import($input);

            response_success(
                [],
                'Tạo đơn nhập hàng thành công.'
            );

        } catch (Throwable $e) {

            response_error(
                $e->getMessage()
            );
        }
    }
}