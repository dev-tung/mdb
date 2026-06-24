<?php

class HomeController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index(): void
    {
        // Danh mục sản phẩm
        $categories = $this->categoryModel->getList();

        // Sản phẩm nổi bật (flag trong DB: is_featured = 1)
        $featuredProducts = $this->productModel->getRackets(6);


        // Có thể thêm:
        // $newProducts = $this->productModel->getLatest(8);

        View::render('home/index', [
            'categories'       => $categories,
            'featuredProducts'  => $featuredProducts,
            // 'newProducts'    => $newProducts
        ]);
    }
}