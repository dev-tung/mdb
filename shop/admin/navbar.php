<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNav">
        <ul class="navbar-nav">
        <!-- SẢN PHẨM DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/product') || active_menu('/product-group') ? 'active' : '' ?>"
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
                <a class="dropdown-item <?= active_menu('/product') ?>"
                href="<?= url('/product') ?>">
                Danh sách
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/product-group') ?>"
                href="<?= url('/product-group') ?>">
                Nhóm sản phẩm
                </a>
            </li>
            </ul>
        </li>

        <!-- KHÁCH HÀNG DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/customer') || active_menu('/customer-group') ? 'active' : '' ?>"
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
                <a class="dropdown-item <?= active_menu('/customer') ?>"
                href="<?= url('/customer') ?>">
                Danh sách
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/customer-group') ?>"
                href="<?= url('/customer-group') ?>">
                Nhóm khách hàng
                </a>
            </li>
            </ul>
        </li>

        <!-- MENU ĐƠN -->
        <li class="nav-item">
            <a class="nav-link <?= active_menu('/supplier') ?>" href="<?= url('/supplier') ?>">
            Nhà cung cấp
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/expense') ?>" href="<?= url('/expense') ?>">
            Chi phí
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/import') ?>" href="<?= url('/import') ?>">
            Nhập hàng
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active_menu('/export') ?>" href="<?= url('/export') ?>">
            Đơn hàng
            </a>
        </li>

        <!-- BÁO CÁO DROPDOWN -->
        <li class="nav-item dropdown">
            <a
            class="nav-link dropdown-toggle <?= active_menu('/report') ?>"
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
                <a class="dropdown-item <?= active_menu('/report/export') ?>"
                href="<?= url('/report/export') ?>">
                Doanh thu
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/report/inventory') ?>"
                href="<?= url('/report/inventory') ?>">
                Tồn kho
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/report/product') ?>"
                href="<?= url('/report/product') ?>">
                Sản phẩm
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= active_menu('/report/customer') ?>"
                href="<?= url('/report/customer') ?>">
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
            <a class="nav-link pe-0" href="<?php echo url('/logout'); ?>"
              onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
              Logout
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>