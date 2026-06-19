<?php
require_once PATH_CUSTOMER . 'repository/customer.php';
require_once PATH_CUSTOMER . 'repository/customer-group.php';

class CustomerController extends BaseController
{
    /* =========================
       LIST CUSTOMERS
    ========================= */

    public function list(): void
    {
        $customers = get_customers_with_group();

        $this->success([
            'data' => $customers
        ]);
    }

    /* =========================
       SHOW CUSTOMER DETAIL
    ========================= */

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->error('Invalid customer id', 400);
        }

        $customer = get_customer_detail($id);

        if (!$customer) {
            $this->error('Customer not found', 404);
        }

        $this->success($customer);
    }

    /* =========================
       SEARCH CUSTOMER (FOR ORDER)
    ========================= */

    public function search(): void
    {
        $keyword = $_GET['keyword'] ?? '';

        $customers = search_customers($keyword);

        $this->success([
            'data' => $customers
        ]);
    }
}