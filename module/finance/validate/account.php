<?php

function validate_account(array $data): array
{
    $errors = [];

    // --------------------
    // NAME
    // --------------------
    if (empty($data['name'])) {
        add_finance_account_error($errors, 'name', 'Account name is required.');
    } elseif (mb_strlen($data['name']) > 255) {
        add_finance_account_error($errors, 'name', 'Account name must be at most 255 characters.');
    }

    // --------------------
    // TYPE
    // --------------------
    $validTypes = ['cash', 'bank', 'wallet', 'debt'];

    if (empty($data['type']) || !in_array($data['type'], $validTypes, true)) {
        add_finance_account_error($errors, 'type', 'Invalid account type.');
    }

    // --------------------
    // INITIAL BALANCE
    // --------------------
    if (!isset($data['initial_balance']) || !is_numeric($data['initial_balance'])) {
        add_finance_account_error($errors, 'initial_balance', 'Initial balance must be a number.');
    } elseif ($data['initial_balance'] < 0) {
        add_finance_account_error($errors, 'initial_balance', 'Initial balance must be >= 0.');
    }

    // --------------------
    // STATUS
    // --------------------
    $validStatus = [0, 1];

    if (!isset($data['status']) || !in_array((int)$data['status'], $validStatus, true)) {
        add_finance_account_error($errors, 'status', 'Invalid status.');
    }

    // --------------------
    // NOTE
    // --------------------
    if (isset($data['note']) && mb_strlen($data['note']) > 500) {
        add_finance_account_error($errors, 'note', 'Note must be at most 500 characters.');
    }

    return $errors;
}

/**
 * Add error helper
 */
function add_finance_account_error(array &$errors, string $field, string $message): void
{
    $errors[$field][] = $message;
}

/**
 * Check validate fail
 */
function finance_account_validate_fails(array $errors): bool
{
    return !empty($errors);
}