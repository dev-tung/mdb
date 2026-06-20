<?php

require_once PATH_SHOP . 'repository/product.php';

class ProductController
{
    /* =========================
       LIST PRODUCTS
    ========================= */

    public function list(): void
    {
        $products = get_products();

        response_success([
            'data' => $products
        ]);
    }

    /* =========================
       SHOW PRODUCT DETAIL
    ========================= */

    public function show(): void
    {
        $slug = trim($_GET['slug'] ?? '');

        if ($slug === '') {
            response_error('Product slug is required', 400);
        }

        $product = get_product_by_slug($slug);

        if (!$product) {
            response_error('Product not found', 404);
        }

        response_success([
            'data' => $product
        ]);
    }

}