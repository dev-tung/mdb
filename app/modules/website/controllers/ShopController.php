<?php

class ShopController
{
    public function index(): void
    {
        View::render('shop/index');
    }

    public function show(): void
    {
        View::render('shop/show');
    }

    public function category(): void {}
    public function search(): void {}
}
