<?php
require_once PATH_ROOT . 'repository/inventory.php';
class InventoryController extends BaseController
{
    public function product(): void
    {
        $products = get_stock_products();
        $this->success($products);
    }
}