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
        $products = $this->productModel->getAll(
            request_filters(['keyword', 'category_id', 'status'])
        );

        View::render('product/index', compact('products'));
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