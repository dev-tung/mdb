<?php require_once ROOT_PATH . 'header.php'; ?>
<?php require_once ROOT_PATH . 'retail/service/product.php'; ?>

<?php $result = product_service(); ?>

<main class="container py-4">

    <div class="row g-4">

        <!-- FILTER -->
        <aside class="col-12 col-lg-3">

            <form method="GET">

                <input type="hidden" name="page" value="<?= $result['page'] ?>">

                <!-- CATEGORY -->
                <div class="card mb-3">
                    <div class="card-header"><b>Danh mục</b></div>
                    <div class="card-body">

                        <select class="form-select" name="type">
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
                </div>

                <!-- BRAND -->
                <div class="card mb-3">
                    <div class="card-header"><b>Thương hiệu</b></div>
                    <div class="card-body">

                        <?php foreach (['yonex','victor','lining','mizuno'] as $b): ?>
                            <div class="form-check">
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
                </div>

                <!-- PRICE -->
                <div class="card mb-3">
                    <div class="card-header"><b>Khoảng giá</b></div>
                    <div class="card-body">

                        <?php foreach ([
                            'lt1'=>'Dưới 1 triệu',
                            '1-3'=>'1 - 3 triệu',
                            '3-5'=>'3 - 5 triệu',
                            'gt5'=>'Trên 5 triệu'
                        ] as $k=>$v): ?>
                            <div class="form-check">
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
                </div>

                <button class="btn btn-success w-100">
                    Lọc sản phẩm
                </button>

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

<?php require_once ROOT_PATH . 'footer.php'; ?>