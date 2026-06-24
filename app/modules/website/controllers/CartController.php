<?php

class CartController
{
    /**
     * Giỏ hàng page
     */
    public function index(): void
    {
        View::render('cart/index');
    }

    /**
     * Checkout page
     */
    public function checkout(): void
    {
        View::render('cart/checkout');
    }

    public function add(): void {}
    public function update(): void {}
    public function remove(): void {}
}
