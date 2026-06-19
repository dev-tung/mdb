<?php
class ImportValidator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validate();
    }

    private function validate(): void
    {
        // --------------------
        // Validate supplier
        // --------------------
        if (!isset($this->data['supplier_id']) || !is_numeric($this->data['supplier_id'])) {
            $this->addError('supplier_id', 'Supplier is required and must be a number.');
        }

        // --------------------
        // Validate description
        // --------------------
        if (isset($this->data['description']) && strlen($this->data['description']) > 255) {
            $this->addError('description', 'Description must be at most 255 characters.');
        }

        // --------------------
        // Validate order status
        // --------------------
        $validStatus = ['pending', 'processing', 'completed', 'cancelled'];
        if (empty($this->data['status']) || !in_array($this->data['status'], $validStatus)) {
            $this->addError('status', 'Invalid order status.');
        }

        // --------------------
        // Validate payment status
        // --------------------
        $validPaymentStatus = ['unpaid', 'partial', 'paid'];
        if (empty($this->data['payment_status']) || !in_array($this->data['payment_status'], $validPaymentStatus)) {
            $this->addError('payment_status', 'Invalid payment status.');
        }

        // --------------------
        // Validate products
        // --------------------
        if (empty($this->data['product']) || !is_array($this->data['product'])) {
            $this->addError('product', 'At least one product is required.');
        } else {
            foreach ($this->data['product'] as $index => $p) {
                if (empty($p['id']) || !is_numeric($p['id'])) {
                    $this->addError("product.$index.id", 'Product ID must be a number.');
                }
                if (!isset($p['quantity']) || !is_numeric($p['quantity']) || $p['quantity'] < 1) {
                    $this->addError("product.$index.quantity", 'Quantity must be >= 1.');
                }
                if (!isset($p['price']) || !is_numeric($p['price']) || $p['price'] < 0) {
                    $this->addError("product.$index.price", 'Price must be >= 0.');
                }
                if (!isset($p['discount']) || !is_numeric($p['discount']) || $p['discount'] < 0) {
                    $this->addError("product.$index.discount", 'Discount must be >= 0.');
                }
                if (!isset($p['is_gift'])) {
                    $this->addError("product.$index.is_gift", 'Gift field is required.');
                }
            }
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
