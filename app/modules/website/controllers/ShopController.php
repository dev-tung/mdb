<?php

class ShopController
{
    protected ProductModel $productModel;
    protected BrandModel $brandModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel   = new ProductModel();
        $this->brandModel   = new BrandModel();
        $this->categoryModel  = new CategoryModel();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getList();
        $brands = $this->brandModel->getList();

        View::render('shop/index', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function show(): void
    {
        View::render('shop/show');
    }

    public function category(): void {}
    public function search(): void {}
}
