<?php

class ProductController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $filters = request_filters(['keyword', 'category_id', 'status']);

        $products = $this->productModel->getList($filters, $limit, $offset);
        $total    = $this->productModel->count($filters);

        $totalPages = (int) ceil($total / $limit);

        View::render('product/index', [
            'products'   => $products,
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
            'limit'      => $limit
        ]);
    }

    public function create(): void
    {
        View::render('product/create');
    }

    public function edit($id): void
    {
        $product = $this->productModel->findById((int)$id)
            or die('Product not found');

        View::render('product/edit', compact('id', 'product'));
    }
}