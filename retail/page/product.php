<?php require_once PATH_ROOT . 'header.php'; ?>
<?php require_once PATH_RETAIL . 'service/product.php'; ?>

<?php $result = product_service(); ?>

<main class="container py-4">

    <div class="row g-4">

        <!-- FILTER -->
        <aside class="col-12 col-lg-3">

            <form method="GET" class="position-sticky" style="top: 20px;">

                <input type="hidden" name="page" value="<?= $result['page'] ?>">

                <!-- FILTER WRAPPER -->
                <div class="border rounded bg-white shadow-sm p-3">

                    <h5 class="fw-bold mb-3 text-success">Bộ lọc</h5>

                    <!-- CATEGORY -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Danh mục</label>

                        <select class="form-select form-select-sm" name="type">
                            <?php foreach ([
                                'all'=>'Tất cả',
                                'racquet'=>'Vợt',
                                'shoes'=>'Giày',
                                'bag'=>'Túi',
                                'accessory'=>'Phụ kiện'
                            ] as $k=>$v): ?>
                                <option value="<?= $k ?>"
                                    <?= $result['filters']['type']==$k?'selected':'' ?>>
                                    <?= $v ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr class="my-3">

                    <!-- BRAND -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Thương hiệu</label>

                        <?php foreach (['yonex','victor','lining','mizuno'] as $b): ?>
                            <div class="form-check small mb-1">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="brand[]"
                                    value="<?= $b ?>"
                                    <?= in_array($b,$result['filters']['brands'])?'checked':'' ?>>
                                <label class="form-check-label">
                                    <?= ucfirst($b) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <hr class="my-3">

                    <!-- PRICE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Khoảng giá</label>

                        <?php foreach ([
                            'lt1'=>'Dưới 1 triệu',
                            '1-3'=>'1 - 3 triệu',
                            '3-5'=>'3 - 5 triệu',
                            'gt5'=>'Trên 5 triệu'
                        ] as $k=>$v): ?>
                            <div class="form-check small mb-1">
                                <input class="form-check-input"
                                    type="radio"
                                    name="price"
                                    value="<?= $k ?>"
                                    <?= $result['filters']['price']==$k?'checked':'' ?>>
                                <label class="form-check-label">
                                    <?= $v ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- BUTTON -->
                    <button class="btn btn-success btn-sm w-100 mt-2">
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

                        <div class="card h-100 border-0 shadow-sm">
                            <img src="https://placehold.co/300x300" class="card-img-top">

                            <div class="card-body">
                                <h6><?= $p['name'] ?></h6>
                                <div class="text-danger fw-bold">
                                    <?= number_format($p['price']) ?>₫
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

            <!-- PAGINATION -->
            <nav class="mt-4">
                <ul class="pagination">

                    <?php for ($i=1; $i <= $result['totalPages']; $i++): ?>
                        <li class="page-item <?= $i==$result['page']?'active':'' ?>">
                            <a class="page-link"
                               href="<?= product_build_query(['page'=>$i]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                </ul>
            </nav>

        </section>

    </div>

</main>

<?php require_once PATH_ROOT . 'footer.php'; ?>