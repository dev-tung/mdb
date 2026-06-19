<?php
require_once PATH_SHOP . 'repository/product.php';

class ProductController extends BaseController
{
    /* =========================
       LIST PRODUCTS
    ========================= */

    public function list(): void
    {
        $products = get_products();
        $this->success($products);
    }

    /* =========================
       SHOW PRODUCT DETAIL
    ========================= */

    public function show(): void
    {
        $slug = $_GET['slug'] ?? '';

        $product = get_product_by_slug($slug);

        if (!$product) {
            $this->error('Product not found', 404);
        }

        $this->success($product);
    }

    /* =========================
       SEARCH PRODUCTS (FOR ORDER)
    ========================= */

    public function search(): void
    {
        $products = get_stock_products();
        $this->success($products);
    }
}