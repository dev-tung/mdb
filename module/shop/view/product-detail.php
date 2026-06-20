<?php require_once PATH_ROOT . 'service/product.php'; ?>

<?php $result = product_detail_service(); ?>

<main class="container py-4">

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="<?= htmlspecialchars($result['product']['main_image'] ?? 'https://placehold.co/600x600') ?>"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="<?= htmlspecialchars($result['product']['name'] ?? '') ?>">

            </div>

            <?php if (!empty($result['product']['images']) && is_array($result['product']['images'])): ?>

                <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                    <?php foreach ($result['product']['images'] as $img): ?>
                        <?php if (!$img) continue; ?>

                        <img src="<?= htmlspecialchars($img) ?>"
                             class="border rounded"
                             style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                             onclick="document.getElementById('mainImg').src=this.src">

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <div class="text-uppercase text-muted small mb-2">
                <?= htmlspecialchars($result['product']['category'] ?? '') ?>
            </div>

            <h1 class="fw-bold mb-3">
                <?= htmlspecialchars($result['product']['name'] ?? '') ?>
            </h1>

            <p class="text-secondary mb-4">
                <?= htmlspecialchars($result['product']['description'] ?? '') ?>
            </p>

            <!-- PRICE -->
            <div class="mb-4">

                <?php if ((float)($result['product']['price'] ?? 0) > 0): ?>

                    <div class="fs-3 fw-bold text-success">
                        <?= number_format((float)($result['product']['price'] ?? 0), 0, ',', '.') ?> ₫
                    </div>

                <?php else: ?>

                    <span class="badge bg-danger px-3 py-2 fs-6">
                        Liên hệ
                    </span>

                <?php endif; ?>

            </div>

            <!-- SPECIFICATIONS -->
            <?php if (!empty($result['product']['specifications']) && is_array($result['product']['specifications'])): ?>

                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">Đặc điểm chi tiết</div>

                    <table class="table table-sm mb-0">

                        <?php foreach ($result['product']['specifications'] as $key => $value): ?>

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

