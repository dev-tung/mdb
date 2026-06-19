<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">

            <ul class="navbar-nav">

                <!-- SẢN PHẨM -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/product') || active_menu('/admin/product-group') ? 'active' : '' ?>"
                        href="#"
                        id="productDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Sản phẩm
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/product') ?>"
                                href="<?= url('/admin/product') ?>">
                                Danh sách
                            </a>
                        </li>
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/product-group') ?>"
                                href="<?= url('/admin/product-group') ?>">
                                Nhóm sản phẩm
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- KHÁCH HÀNG -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/customer') || active_menu('/admin/customer-group') ? 'active' : '' ?>"
                        href="#"
                        id="customerDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Khách hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/customer') ?>"
                                href="<?= url('/admin/customer') ?>">
                                Danh sách
                            </a>
                        </li>
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/customer-group') ?>"
                                href="<?= url('/admin/customer-group') ?>">
                                Nhóm khách hàng
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- NHÀ CUNG CẤP -->
                <li class="nav-item">
                    <a
                        class="nav-link <?= active_menu('/admin/supplier') ?>"
                        href="<?= url('/admin/supplier') ?>">
                        Nhà cung cấp
                    </a>
                </li>

                <!-- CHI PHÍ -->
                <li class="nav-item">
                    <a
                        class="nav-link <?= active_menu('/admin/expense') ?>"
                        href="<?= url('/admin/expense') ?>">
                        Chi phí
                    </a>
                </li>

                <!-- NHẬP HÀNG -->
                <li class="nav-item">
                    <a
                        class="nav-link <?= active_menu('/admin/import') ?>"
                        href="<?= url('/admin/import') ?>">
                        Nhập hàng
                    </a>
                </li>

                <!-- ĐƠN HÀNG -->
                <li class="nav-item">
                    <a
                        class="nav-link <?= active_menu('/admin/export/create') ?>"
                        href="<?= url('/admin/export/create') ?>">
                        Đơn hàng
                    </a>
                </li>

                <!-- BÁO CÁO -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/report') ?>"
                        href="#"
                        id="reportDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Báo cáo
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/report/export') ?>"
                                href="<?= url('/admin/report/export') ?>">
                                Doanh thu
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/report/inventory') ?>"
                                href="<?= url('/admin/report/inventory') ?>">
                                Tồn kho
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/report/product') ?>"
                                href="<?= url('/admin/report/product') ?>">
                                Sản phẩm
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/report/customer') ?>"
                                href="<?= url('/admin/report/customer') ?>">
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

