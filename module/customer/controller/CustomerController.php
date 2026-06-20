<?php

require_once PATH_CUSTOMER . 'repository/customer.php';

class CustomerController
{
    /**
     * GET /api/customer
     */
    public function list(): void
    {
        $customers = get_customers_with_group();

        response_success([
            'data' => $customers,
        ]);
    }

    /**
     * GET /api/customer/show?id=1
     */
    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            response_error('Invalid customer id', 400);
            return;
        }

        $customer = get_customer_detail($id);

        if (!$customer) {
            response_error('Customer not found', 404);
            return;
        }

        response_success([
            'data' => $customer,
        ]);
    }

    /**
     * GET /api/customer/search?keyword=tung
     */
    public function search(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');

        $customers = search_customers($keyword);

        response_success([
            'data' => $customers,
        ]);
    }
}