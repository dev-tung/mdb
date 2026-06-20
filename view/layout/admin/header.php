<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= URL_PUBLIC ?>css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">

            <ul class="navbar-nav">

                <!-- Tài chính -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/finance') ?>"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Tài chính
                    </a>

                    <ul class="dropdown-menu">

                        <!-- Tài khoản -->
                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/account') ?>">
                                Tài khoản
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/account/create') ?>">
                                Thêm tài khoản
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Danh mục -->
                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/category') ?>">
                                Danh mục
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/category/create') ?>">
                                Thêm danh mục
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Giao dịch -->
                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/transaction') ?>">
                                Giao dịch
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/transaction/create') ?>">
                                Thêm giao dịch
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Công nợ -->
                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/debt') ?>">
                                Công nợ
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/debt/create') ?>">
                                Thêm công nợ
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Báo cáo -->
                        <li>
                            <a
                                class="dropdown-item"
                                href="<?= url('/admin/finance/report') ?>">
                                Báo cáo
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- SHOP -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/product') || active_menu('/admin/export') || active_menu('/admin/import') ? 'active' : '' ?>"
                        href="#"
                        id="productDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Cửa hàng
                    </a>

                    <ul class="dropdown-menu">

                        <!-- Sản phẩm -->
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/product') ?>"
                                href="<?= url('/admin/product') ?>">
                                Sản phẩm
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/product/create') ?>"
                                href="<?= url('/admin/product/create') ?>">
                                Thêm sản phẩm
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Đơn hàng -->
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/export') ?>"
                                href="<?= url('/admin/export') ?>">
                                Đơn hàng
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/export/create') ?>"
                                href="<?= url('/admin/export/create') ?>">
                                Thêm đơn hàng
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Phiếu mua -->
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/import') ?>"
                                href="<?= url('/admin/import') ?>">
                                Phiếu mua
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/import/create') ?>"
                                href="<?= url('/admin/import/create') ?>">
                                Thêm phiếu mua
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Khách hàng -->
                <li class="nav-item">
                    <a
                        class="nav-link <?= active_menu('/admin/customer') ?>"
                        href="<?= url('/admin/customer') ?>">
                        Khách hàng
                    </a>
                </li>

            </ul>

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item me-4 position-relative">

                    <a
                        href="#"
                        id="notificationBell"
                        class="nav-link d-flex align-items-center position-relative">

                        <span
                            class="position-absolute badge rounded-pill bg-danger"
                            style="display:none;">
                            0
                        </span>

                    </a>

                    <div
                        id="notificationDropdown"
                        class="dropdown-menu p-0"
                        style="top:100%; right:0; display:none; min-width:320px; height:500px; overflow-y:auto; overflow-x:hidden;">
                    </div>

                </li>

                <li class="nav-item">
                    <a
                        class="nav-link pe-0"
                        href="<?= url('/admin/logout') ?>"
                        onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
                        Logout
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>

