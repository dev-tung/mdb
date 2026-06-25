<main class="container py-4">

    <?php
        // normalize image url
        function img_url($img) {
            if (!$img) return 'https://placehold.co/600x600';
            if (str_starts_with($img, 'http')) return $img;
            return '/' . ltrim($img, '/');
        }
    ?>

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <?php
                $mainImage = img_url($product['thumbnail'] ?? null);
            ?>

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="<?= htmlspecialchars($mainImage) ?>"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="<?= htmlspecialchars($product['name'] ?? '') ?>">

            </div>

            <!-- GALLERY -->
            <?php if (!empty($product['gallery']) && is_array($product['gallery'])): ?>
                <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                    <?php foreach ($product['gallery'] as $img): ?>
                        <?php $imgUrl = img_url($img); ?>

                        <img src="<?= htmlspecialchars($imgUrl) ?>"
                             class="border rounded"
                             style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                             onclick="document.getElementById('mainImg').src=this.src">

                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <!-- CATEGORY -->
            <div class="text-uppercase text-muted small mb-2">
                <?= htmlspecialchars($product['category_name'] ?? '') ?>
            </div>

            <!-- NAME -->
            <h1 class="fw-bold mb-3">
                <?= htmlspecialchars($product['name'] ?? '') ?>
            </h1>

            <!-- DESCRIPTION -->
            <?php if (!empty($product['description'])): ?>
                <p class="text-secondary mb-4">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>
            <?php endif; ?>

            <!-- PRICE -->
            <div class="mb-4">

                <?php if (($product['price'] ?? 0) > 0): ?>
                    <div class="fs-3 fw-bold text-success">
                        <?= number_format($product['price'], 0, ',', '.') ?> ₫
                    </div>
                <?php else: ?>
                    <div class="fs-5 fw-bold text-danger">
                        Tạm hết hàng
                    </div>
                <?php endif; ?>

                <div class="text-muted mt-2">
                    Liên hệ đặt hàng 0973.359.165
                </div>

            </div>

            <!-- ATTRIBUTES -->
            <?php if (!empty($product['attributes']) && is_array($product['attributes'])): ?>
                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">Đặc điểm chi tiết</div>

                    <table class="table table-sm mb-0">

                        <?php foreach ($product['attributes'] as $attr): ?>
                            <tr>
                                <th class="text-muted fw-normal">
                                    <?= htmlspecialchars($attr['attribute_name']) ?>
                                </th>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($attr['attribute_value']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>

                </div>
            <?php endif; ?>

        </div>

    </div>

</main>