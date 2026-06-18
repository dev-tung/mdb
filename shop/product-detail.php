<?php require_once PATH_ROOT . 'header.php'; ?>
<?php require_once PATH_SHOP . 'service/product-detail.php'; ?>

<?php
$result = product_detail_service();
$product = $result['product'];
?>

<main class="container py-4">

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="<?= htmlspecialchars($product['main_image']) ?>"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="<?= htmlspecialchars($product['name']) ?>">

            </div>

            <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                <?php foreach ($product['images'] as $img): ?>

                    <img src="<?= htmlspecialchars($img) ?>"
                         class="border rounded"
                         style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                         onclick="document.getElementById('mainImg').src=this.src">

                <?php endforeach; ?>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <div class="text-uppercase text-muted small mb-2">
                <?= htmlspecialchars($product['category']) ?>
            </div>

            <h1 class="fw-bold mb-3">
                <?= htmlspecialchars($product['name']) ?>
            </h1>

            <p class="text-secondary mb-4">
                <?= htmlspecialchars($product['description']) ?>
            </p>

            <div class="fs-3 fw-bold mb-4">
                <?= number_format($product['price'], 0, ',', '.') ?> ₫
            </div>

            <?php if (!empty($product['specifications'])): ?>

                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">Specifications</div>

                    <table class="table table-sm mb-0">

                        <?php foreach ($product['specifications'] as $key => $value): ?>

                            <tr>
                                <th class="text-muted fw-normal">
                                    <?= htmlspecialchars($key) ?>
                                </th>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($value) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</main>

<?php require_once PATH_ROOT . 'footer.php'; ?>