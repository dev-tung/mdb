
<?php require_once PATH_REPOSITORY. 'product.php'; ?>

<?php

$q = trim($_GET['q'] ?? '');

$products = $q
    ? search_products($q)
    : [];

?>

<main class="container py-4">

    <h1 class="fw-bold mb-3">
        Kết quả tìm kiếm
    </h1>

    <?php if ($q): ?>

        <p class="text-muted mb-4">
            Từ khóa:
            <strong><?= htmlspecialchars($q) ?></strong>
            —
            tìm thấy
            <strong><?= count($products) ?></strong>
            sản phẩm
        </p>

    <?php endif; ?>

    <div class="row g-3">

        <?php if (!$products): ?>

            <div class="col-12">

                <div class="alert alert-warning">

                    <?= $q
                        ? 'Không tìm thấy sản phẩm phù hợp.'
                        : 'Vui lòng nhập từ khóa tìm kiếm.' ?>

                </div>

            </div>

        <?php endif; ?>

        <?php foreach ($products as $p): ?>

            <?php
            $img = !empty($p['thumbnail'])
                ? (
                    str_starts_with($p['thumbnail'], 'http')
                    ? $p['thumbnail']
                    : URL_ROOT . '/shop/' . ltrim($p['thumbnail'], '/')
                )
                : 'https://placehold.co/300x300?text=No+Image';

            $price = (int)($p['price'] ?? 0);
            ?>

            <div class="col-6 col-md-3 col-xl-2">

                <a href="/product/<?= urlencode($p['slug']) ?>"
                   class="text-decoration-none text-dark">

                    <div class="card h-100 border-0 shadow-sm">

                        <div class="ratio ratio-1x1 bg-light">

                            <img
                                src="<?= htmlspecialchars($img) ?>"
                                alt="<?= htmlspecialchars($p['name']) ?>"
                                class="w-100 h-100 p-2"
                                style="object-fit:contain;">

                        </div>

                        <div class="card-body">

                            <div class="small text-muted mb-1">

                                <?= htmlspecialchars($p['brand_name'] ?? '') ?>

                            </div>

                            <h6 class="mb-2">

                                <?= htmlspecialchars($p['name']) ?>

                            </h6>

                            <div class="text-danger fw-bold">

                                <?= $price
                                    ? number_format($price) . '₫'
                                    : 'Liên hệ' ?>

                            </div>

                        </div>

                    </div>

                </a>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<?php require_once PATH_ROOT . 'partial/footer.php'; ?>