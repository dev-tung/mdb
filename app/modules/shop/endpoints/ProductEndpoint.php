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
        header('Content-Type: application/json');

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $filters = request_filters(['keyword', 'category_id', 'status']);

        $products = $this->productModel->getList($filters, $limit, $offset);
        $total    = $this->productModel->count($filters);

        echo json_encode([
            'data'       => $products,
            'page'       => $page,
            'total'      => $total,
            'totalPages' => ceil($total / $limit)
        ]);
    }
}