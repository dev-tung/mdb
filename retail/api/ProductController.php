<?php
require_once PATH_RETAIL . 'repository/product.php';

class ProductController extends BaseController
{
    public function index(): void
    {
        $products = get_products();

        $this->success($products);
    }

    public function show(): void
    {
        $slug = $_GET['slug'] ?? '';

        $product = get_product_by_slug($slug);

        if (!$product) {
            $this->error('Product not found', 404);
        }

        $this->success($product);
    }
}