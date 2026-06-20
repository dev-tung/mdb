<?php

function validate_export(array $data): array
{
    $errors = [];

    // --------------------
    // Validate customer
    // --------------------
    if (empty($data['customer_id']) || !is_numeric($data['customer_id'])) {
        add_export_error($errors, 'customer_id', 'Customer is required and must be a number.');
    }

    // --------------------
    // Validate description
    // --------------------
    if (isset($data['description']) && strlen($data['description']) > 255) {
        add_export_error($errors, 'description', 'Description must be at most 255 characters.');
    }

    // --------------------
    // Validate order status
    // --------------------
    $validStatus = ['pending', 'processing', 'completed', 'cancelled'];
    if (empty($data['status']) || !in_array($data['status'], $validStatus, true)) {
        add_export_error($errors, 'status', 'Invalid order status.');
    }

    // --------------------
    // Validate payment status
    // --------------------
    $validPaymentStatus = ['unpaid', 'partial', 'paid'];
    if (empty($data['payment_status']) || !in_array($data['payment_status'], $validPaymentStatus, true)) {
        add_export_error($errors, 'payment_status', 'Invalid payment status.');
    }

    // --------------------
    // Validate products
    // --------------------
    if (empty($data['product']) || !is_array($data['product'])) {
        add_export_error($errors, 'product', 'At least one product is required.');
    } else {
        foreach ($data['product'] as $index => $p) {

            if (empty($p['id']) || !is_numeric($p['id'])) {
                add_export_error($errors, "product.$index.id", 'Product ID must be a number.');
            }

            if (!isset($p['quantity']) || !is_numeric($p['quantity']) || $p['quantity'] < 1) {
                add_export_error($errors, "product.$index.quantity", 'Quantity must be >= 1.');
            }

            if (!isset($p['price']) || !is_numeric($p['price']) || $p['price'] < 0) {
                add_export_error($errors, "product.$index.price", 'Price must be >= 0.');
            }

            if (!isset($p['discount']) || !is_numeric($p['discount']) || $p['discount'] < 0) {
                add_export_error($errors, "product.$index.discount", 'Discount must be >= 0.');
            }
        }
    }

    return $errors;
}

/**
 * Helper add error
 */
function add_export_error(array &$errors, string $field, string $message): void
{
    $errors[$field][] = $message;
}

/**
 * Check fail
 */
function export_validate_fails(array $errors): bool
{
    return !empty($errors);
}