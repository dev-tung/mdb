<?php

class TransactionController
{
    public function list(): void
    {
        response_success([
            'data' => get_finance_transactions()
        ]);
    }

    public function show(): void
    {
        $input = request_input();
        $id = $input['id'] ?? 0;

        if ($id <= 0) {
            response_error('ID không hợp lệ');
        }

        $data = DB::row("SELECT * FROM finance_transaction WHERE id = ?", [$id]);

        if (!$data) {
            response_error('Không tìm thấy giao dịch', 404);
        }

        response_success([
            'data' => $data
        ]);
    }

    public function create(): void
    {
        $input = request_input();

        try {

            DB::create('finance_transaction', [
                'account_id'       => $input['account_id'],
                'category_id'      => $input['category_id'],
                'module'           => $input['module'] ?? 'manual',
                'amount'           => $input['amount'],
                'transaction_date' => $input['transaction_date'],
                'reference_type'   => $input['reference_type'] ?? null,
                'reference_id'     => $input['reference_id'] ?? null,
                'is_manual'        => $input['is_manual'] ?? 1,
                'note'             => $input['note'] ?? '',
                'created_by'       => $input['created_by'] ?? 0,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);

            response_success([], 'Tạo giao dịch thành công');

        } catch (Throwable $e) {
            response_error($e->getMessage());
        }
    }

    public function update(): void
    {
        $input = request_input();

        if (empty($input['id'])) {
            response_error('ID không hợp lệ');
        }

        try {

            DB::update('finance_transaction', [
                'account_id'       => $input['account_id'],
                'category_id'      => $input['category_id'],
                'amount'           => $input['amount'],
                'transaction_date' => $input['transaction_date'],
                'note'             => $input['note'] ?? '',
                'updated_at'       => date('Y-m-d H:i:s'),
            ], [
                'id' => $input['id']
            ]);

            response_success([], 'Cập nhật giao dịch thành công');

        } catch (Throwable $e) {
            response_error($e->getMessage());
        }
    }

    public function delete(): void
    {
        $input = request_input();

        DB::delete('finance_transaction', [
            'id' => $input['id']
        ]);

        response_success([], 'Xóa giao dịch thành công');
    }
}