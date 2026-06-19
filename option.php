<?php
return [

    // Trạng thái đơn hàng
    'product_status' => [
        'pending'    => 'Chưa xử lý',
        'processing' => 'Đang xử lý',
        'completed'  => 'Hoàn thành',
        'cancelled'  => 'Hủy',
    ],

    // Trạng thái thanh toán
    'payment_status' => [
        'unpaid'     => 'Chưa thanh toán',
        'paid'       => 'Đã thanh toán'
    ],

    // Giới tính
    'person_gender' => [
        'male'   => 'Nam',
        'female' => 'Nữ',
        'other'  => 'Khác',
    ],

    // Loại chi phí (map với category_id trong DB)
    'expense_category' => [
        1 => 'Thuê mặt bằng',
        2 => 'Lương nhân viên',
        3 => 'Điện nước',
        4 => 'Marketing',
        5 => 'Vận hành',
        6 => 'Khác',
    ],

    'active_status' => [
        'active'   => 'Đang bán',
        'inactive' => 'Ngừng bán',
    ],

];