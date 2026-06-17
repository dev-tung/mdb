<header class="border-bottom bg-white">
    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light py-3">

            <!-- Logo -->
            <a class="navbar-brand fw-bold me-4" href="<?= ROOT_URL ?>">
                MDB SHOP
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <!-- NAV -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT_URL ?>">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT_URL ?>/product">Sản phẩm</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT_URL ?>/brand">Thương hiệu</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT_URL ?>/contact">Liên hệ</a>
                    </li>

                </ul>

                <!-- SEARCH (desktop compact) -->
                <form class="d-flex me-lg-3 mb-3 mb-lg-0"
                      style="max-width: 280px;"
                      method="get"
                      action="<?= ROOT_URL ?>/search">

                    <input class="form-control form-control-sm me-2"
                           type="search"
                           name="keyword"
                           placeholder="Tìm kiếm...">

                    <button class="btn btn-outline-primary btn-sm">
                        Tìm
                    </button>

                </form>

                <!-- ACTIONS -->
                <div class="d-flex gap-2">

                    <a href="<?= ROOT_URL ?>/account"
                       class="btn btn-outline-secondary btn-sm">
                        Tài khoản
                    </a>

                    <a href="<?= ROOT_URL ?>/cart"
                       class="btn btn-primary btn-sm">
                        Giỏ hàng
                    </a>

                </div>

            </div>

        </nav>

    </div>
</header>