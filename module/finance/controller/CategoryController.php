<?php

class CategoryController
{
    public function list(): void
    {
        response_success([
            'data' => DB::all("SELECT * FROM finance_category ORDER BY sort_order ASC, id DESC")
        ]);
    }

    public function show(): void
    {
        $input = request_input();
        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            response_error("ID không hợp lệ");
        }

        $data = DB::row("SELECT * FROM finance_category WHERE id = ?", [$id]);

        if (!$data) {
            response_error("Không tìm thấy");
        }

        response_success([
            'data' => $data
        ]);
    }

    public function create(): void
    {
        $input = request_input();

        if (empty($input['name'])) {
            response_error("Name required");
        }

        DB::insert("finance_category", [
            'name'        => $input['name'],
            'type'        => $input['type'] ?? 'income',
            'sort_order'  => (int)($input['sort_order'] ?? 0),
            'status'      => (int)($input['status'] ?? 1),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        response_success([], "Created");
    }

    public function update(): void
    {
        $input = request_input();
        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            response_error("Invalid ID");
        }

        DB::update("finance_category", [
            'name'        => $input['name'],
            'type'        => $input['type'],
            'sort_order'  => (int)$input['sort_order'],
            'status'      => (int)$input['status'],
            'updated_at'  => date('Y-m-d H:i:s'),
        ], [
            'id' => $id
        ]);

        response_success([], "Updated");
    }

    public function delete(): void
    {
        $input = request_input();
        $id = (int)($input['id'] ?? 0);

        DB::delete("finance_category", [
            'id' => $id
        ]);

        response_success([], "Deleted");
    }
}