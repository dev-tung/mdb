<?php require_once PATH_ROOT . 'header.php'; ?>
<?php require_once PATH_SHOP . 'service/product-detail.php'; ?>

<?php
$result = product_detail_service();
$product = $result['product'] ?? null;

if (!$product) {
    echo "<div class='container py-5'>Product not found</div>";
    require_once PATH_ROOT . 'footer.php';
    return;
}

/**
 * FIX: dùng images từ service
 */
$images = $product['images'] ?? [];

/**
 * fallback ảnh chính
 */
$mainImage = $images[0] ?? 'https://placehold.co/600x600';

/**
 * helper: xử lý URL ảnh
 */
function product_img($img)
{
    if (empty($img)) return 'https://placehold.co/600x600';

    // nếu là full URL
    if (str_starts_with($img, 'http')) {
        return $img;
    }

    return URL_ROOT . '/shop/' . ltrim($img, '/');
}
?>

<main class="container py-4">

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="<?= htmlspecialchars(product_img($mainImage)) ?>"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="<?= htmlspecialchars($product['name']) ?>">

            </div>

            <!-- THUMBNAILS -->
            <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                <?php foreach ($images as $img): ?>

                    <img src="<?= htmlspecialchars(product_img($img)) ?>"
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
                <?= !empty($product['description'])
                    ? htmlspecialchars($product['description'])
                    : 'Sản phẩm chính hãng, tối ưu cho thi đấu và luyện tập.' ?>
            </p>

            <div class="fs-3 fw-bold mb-4">

                <?php if (!empty($product['price'])): ?>
                    <span class="text-danger">
                        <?= number_format($product['price'], 0, ',', '.') ?> ₫
                    </span>
                <?php else: ?>
                    <span class="text-success">Liên hệ</span>
                <?php endif; ?>

            </div>

            <!-- SPECS -->
            <?php if (!empty($product['specifications'])): ?>

                <div class="border rounded p-3 bg-light">

                    <div class="fw-bold mb-3">
                        Specifications
                    </div>

                    <table class="table table-sm mb-0">

                        <?php foreach ($product['specifications'] as $key => $value): ?>
                            <tr>
                                <th class="text-muted fw-normal">
                                    <?= htmlspecialchars($key) ?>
                                </th>
                                <td class="fw-semibold">
                                    <?= nl2br(htmlspecialchars($value)) ?>
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