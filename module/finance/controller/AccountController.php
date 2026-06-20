<?php

require_once PATH_FINANCE . 'repository/account.php';
require_once PATH_FINANCE . 'validate/account.php';

class AccountController
{
    /* =========================
       LIST
    ========================= */

    public function list(): void
    {
        response_success([
            'data' => get_finance_accounts()
        ]);
    }

    /* =========================
       CREATE
    ========================= */

    public function create(): void
    {
        $input = request_input();



        $errors = validate_account($input);

        if (finance_account_validate_fails($errors)) {
            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            DB::create('finance_account', [
                'name'            => $input['name'],
                'type'            => $input['type'],
                'initial_balance' => (float) ($input['initial_balance'] ?? 0),
                'status'          => (int) ($input['status'] ?? 1),
                'note'            => $input['note'] ?? '',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            response_success([], 'Tạo tài khoản thành công.');

        } catch (Throwable $e) {

            response_error('Lỗi khi tạo tài khoản: ' . $e->getMessage());
        }
    }

    /* =========================
       UPDATE
    ========================= */

    public function update(): void
    {
        $input = request_input();

        $id = $input['id'] ?? 0;

        $errors = validate_account($input);

        if (finance_account_validate_fails($errors)) {
            response_error(
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $errors]
            );
        }

        try {

            DB::update('finance_account', [
                'name'            => $input['name'],
                'type'            => $input['type'],
                'initial_balance' => (float) ($input['initial_balance'] ?? 0),
                'status'          => (int) ($input['status'] ?? 1),
                'note'            => $input['note'] ?? '',
                'updated_at'      => date('Y-m-d H:i:s'),
            ], [
                'id' => (int) $id
            ]);

            response_success([], 'Cập nhật tài khoản thành công.');

        } catch (Throwable $e) {

            response_error('Lỗi khi cập nhật tài khoản: ' . $e->getMessage());
        }
    }

    /* =========================
       SHOW
    ========================= */

    public function show(): void
    {
        $input = request_input();

        $id = $input['id'] ?? 0;

        if (!is_numeric($id) || $id <= 0) {
            response_error('ID không hợp lệ.', 400);
        }

        try {

            $account = DB::row(
                "SELECT *
                 FROM finance_account
                 WHERE id = ?
                 LIMIT 1",
                [$id]
            );

            if (!$account) {
                response_error('Không tìm thấy tài khoản.', 404);
            }

            response_success([
                'data' => $account
            ]);

        } catch (Throwable $e) {

            response_error('Lỗi khi lấy dữ liệu tài khoản.');
        }
    }

    /* =========================
       DELETE
    ========================= */

    public function delete(): void
    {
        $input = request_input();

        $id = $input['id'] ?? 0;

        if (empty($id)) {
            response_error('ID không hợp lệ.');
        }

        try {

            DB::delete('finance_account', [
                'id' => (int) $id
            ]);

            response_success([], 'Xóa tài khoản thành công.');

        } catch (Throwable $e) {

            response_error('Lỗi khi xóa tài khoản: ' . $e->getMessage());
        }
    }
}