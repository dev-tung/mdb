<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">
        <ul class="navbar-nav">

        <!-- SẢN PHẨM DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/products') || active_menu('/products/groups') ? 'active' : '' ?>"
            href="#"
            id="productDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            >
            Sản phẩm
            </a>

            <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item <?= active_menu('/products') ?>"
                href="<?= route('/admin/products') ?>">
                Danh sách
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/products/groups') ?>"
                href="<?= route('/admin/products/groups') ?>">
                Nhóm sản phẩm
                </a>
            </li>
            </ul>
        </li>

        <!-- KHÁCH HÀNG DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/customers') || active_menu('/customers/groups') ? 'active' : '' ?>"
            href="#"
            id="customerDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            >
            Khách hàng
            </a>

            <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item <?= active_menu('/customers') ?>"
                href="<?= route('/admin/customers') ?>">
                Danh sách
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/customers/groups') ?>"
                href="<?= route('/admin/customers/groups') ?>">
                Nhóm khách hàng
                </a>
            </li>
            </ul>
        </li>

        <!-- MENU ĐƠN -->
        <li class="nav-item">
            <a class="nav-link <?= active_menu('/suppliers') ?>" href="<?= route('/admin/suppliers') ?>">
            Nhà cung cấp
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/expenses') ?>" href="<?= route('/admin/expenses') ?>">
            Chi phí
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/purchases') ?>" href="<?= route('/admin/purchases') ?>">
            Nhập hàng
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/orders') ?>" href="<?= route('/admin/orders') ?>">
            Đơn hàng
            </a>
        </li>

        <!-- BÁO CÁO DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/reports') ? 'active' : '' ?>"
            href="#"
            id="reportDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            >
            Báo cáo
            </a>

            <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item <?= active_menu('/reports/revenue') ?>"
                href="<?= route('/admin/reports/revenue') ?>">
                Doanh thu
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/reports/inventory') ?>"
                href="<?= route('/admin/reports/inventory') ?>">
                Tồn kho
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/reports/products') ?>"
                href="<?= route('/admin/reports/products') ?>">
                Sản phẩm
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/reports/customers') ?>"
                href="<?= route('/admin/reports/customers') ?>">
                Khách hàng
                </a>
            </li>
            </ul>
        </li>

        </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-4 position-relative">
            <a
                href="#"
                id="notificationBell"
                class="nav-link d-flex align-items-center position-relative"
            >
                <span class="position-absolute badge rounded-pill bg-danger" style="display:none;">0</span>
            </a>

            <div
                id="notificationDropdown"
                class="dropdown-menu p-0"
                style="top:100%; right:0; display:none; min-width:320px; height:500px; overflow-y:auto; overflow-x:hidden;"
            >
                <!-- Header + Items sẽ load bằng JS -->
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link pe-0" href="<?php echo route('/admin/logout'); ?>"
              onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
              Logout
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>