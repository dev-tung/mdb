<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="<?= URL_PUBLIC ?>css/style.css">

</head>
<body>
    <header class="border-bottom bg-white">
        <div class="container">

            <nav class="navbar navbar-expand-lg navbar-light py-3">

                <!-- Logo -->
                <a class="navbar-brand fw-bold me-4" style="color: #26750e" href="<?= URL_ROOT ?>">
                    MDB SHOP
                </a>

                <!-- Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a
                                class="nav-link <?= active_menu_exact('/') ?>"
                                href="<?= URL_ROOT ?>"
                            >
                                Trang chủ
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= active_menu('product') ?>"
                                href="<?= URL_ROOT ?>product"
                            >
                                Sản phẩm
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= active_menu('string') ?>"
                                href="<?= URL_ROOT ?>string"
                            >
                                Căng cước
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= active_menu('affilate') ?>"
                                href="<?= URL_ROOT ?>affilate"
                            >
                                CTV
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= active_menu('recruitment') ?>"
                                href="<?= URL_ROOT ?>recruitment"
                            >
                                Tuyển dụng
                            </a>
                        </li>

                    </ul>

                    <!-- SEARCH (desktop compact) -->
                    <form class="d-flex flex-grow-1 mx-lg-4 mb-3 mb-lg-0 border border-success rounded overflow-hidden"
                        method="get"
                        action="<?= URL_ROOT ?>product">

                        <input
                            class="form-control border-0 shadow-none"
                            type="search"
                            name="keyword"
                            placeholder="Tìm kiếm đồ cầu lông..."
                            value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">

                        <button class="btn btn-sm rounded-0 d-flex align-items-center justify-content-center px-3"
                                type="submit">

                            <!-- Search icon outline (thin style) -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="M20 20l-3.5-3.5"></path>
                            </svg>

                        </button>

                    </form>

                    <!-- ACTIONS -->
                    <div class="d-flex gap-2">

                        <a href="#"
                        class="btn btn-outline-secondary btn-sm">
                            Tài khoản
                        </a>

                        <a href="#"
                        class="btn btn-success btn-sm">
                            Giỏ hàng
                        </a>

                    </div>

                </div>

            </nav>

        </div>
    </header>