<?php

class ProductEndpoint
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel   = new ProductModel();
    }

    public function apiList()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = Config::get('pagination', 'default_per_page');

        $filters = request_filters(['keyword', 'category_id', 'status']);

        $products = $this->productModel->getList(
            $filters,
            $limit,
            ($page - 1) * $limit
        );

        $total = $this->productModel->count($filters);

        return Response::json([
            'data' => $products,
            'meta' => [
                'page'       => $page,
                'total'      => $total,
                'totalPages' => ceil($total / $limit),
                'perPage'    => $limit
            ]
        ]);
    }

    public function apiDelete()
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            return Response::json([
                'success' => false,
                'message' => 'ID không hợp lệ'
            ]);
        }

        $deleted = $this->productModel->deleteById($id);

        return Response::json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Xóa thành công' : 'Không tìm thấy sản phẩm'
        ]);
    }
}