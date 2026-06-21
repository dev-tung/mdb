<?php

class ProductController
{
    public function index()
    {
        echo "Product list";
    }

    public function show($id)
    {
        echo "Product ID: " . $id;
    }
}