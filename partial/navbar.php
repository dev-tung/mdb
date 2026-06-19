<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">

            <ul class="navbar-nav">

                <!-- SHOP -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle <?= active_menu('/admin/product') || active_menu('/admin/product') ? 'active' : '' ?>"
                        href="#"
                        id="productDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Cửa hàng
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/product') ?>"
                                href="<?= url('/admin/product') ?>">
                                Sản phẩm
                            </a>
                        </li>
                        <li>
                            <a
                                class="dropdown-item <?= active_menu('/admin/export/create') ?>"
                                href="<?= url('/admin/export/create') ?>">
                                Thêm đơn hàng
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

