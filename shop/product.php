<?php require_once PATH_ROOT . 'header.php'; ?>
<?php require_once PATH_SHOP . 'service/product.php'; ?>

<?php $result = product_service(); ?>

<main class="container py-4">

    <div class="row g-4">

        <!-- FILTER -->
        <aside class="col-12 col-lg-3">

            <form method="GET" class="position-sticky" style="top:20px;">

                <input type="hidden" name="page" value="<?= $result['page'] ?>">

                <div class="border rounded bg-white shadow-sm p-3">

                    <h5 class="fw-bold mb-3 text-default">
                        Bộ lọc
                    </h5>

                    <!-- CATEGORY -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Danh mục
                        </label>

                        <select
                            class="form-select form-select-sm"
                            name="category">

                            <option value="0">
                                Tất cả danh mục
                            </option>

                            <?php foreach ($result['categories'] as $category): ?>

                                <option
                                    value="<?= $category['id'] ?>"
                                    <?= $result['filters']['category'] == $category['id'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($category['name']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <hr class="my-3">

                    <!-- BRAND -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Thương hiệu
                        </label>

                        <?php foreach ($result['brands'] as $brand): ?>

                            <div class="form-check small mb-1">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="brand[]"
                                    value="<?= $brand['id'] ?>"
                                    <?= in_array($brand['id'], $result['filters']['brands']) ? 'checked' : '' ?>>

                                <label class="form-check-label">

                                    <?= htmlspecialchars($brand['name']) ?>

                                </label>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <hr class="my-3">

                    <!-- PRICE -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Khoảng giá
                        </label>

                        <?php foreach ([
                            'lt1' => 'Dưới 1 triệu',
                            '1-3' => '1 - 3 triệu',
                            '3-5' => '3 - 5 triệu',
                            'gt5' => 'Trên 5 triệu'
                        ] as $k => $v): ?>

                            <div class="form-check small mb-1">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="price"
                                    value="<?= $k ?>"
                                    <?= $result['filters']['price'] == $k ? 'checked' : '' ?>>

                                <label class="form-check-label">

                                    <?= $v ?>

                                </label>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button class="btn btn-success btn-sm w-100">
                        Lọc sản phẩm
                    </button>

                </div>

            </form>

        </aside>

        <!-- PRODUCTS -->
        <section class="col-12 col-lg-9">

            <div class="row g-3">

                <?php foreach ($result['products'] as $p): ?>

                    <div class="col-6 col-md-4 col-xl-3">

                        <a href="/product/<?= urlencode($p['slug']) ?>"
                        class="text-decoration-none text-dark">

                            <div class="card h-100 border-0 shadow-sm">

                                <div class="ratio ratio-1x1 bg-light">

                                    <img
                                        src="<?= htmlspecialchars(product_card_image($p['thumbnail'])) ?>"
                                        alt="<?= htmlspecialchars($p['name']) ?>"
                                        class="w-100 h-100 p-2"
                                        style="object-fit:contain;">

                                </div>

                                <div class="card-body">

                                    <h6 class="mb-2">
                                        <?= htmlspecialchars($p['name']) ?>
                                    </h6>

                                    <div class="text-danger fw-bold">
                                        <?= product_card_price($p['price']) ?>
                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

                <?php if (empty($result['products'])): ?>

                    <div class="col-12">
                        <div class="alert alert-light border text-center">
                            Không tìm thấy sản phẩm phù hợp.
                        </div>
                    </div>

                <?php endif; ?>

            </div>

            <!-- PAGINATION -->
            <?php
                echo pager([
                    'page'  => $result['page'],
                    'total' => $result['totalPages'],
                    'query' => $_GET,
                    'color' => true
                ]);
            ?>

        </section>

    </div>

</main>

<?php require_once PATH_ROOT . 'footer.php'; ?>