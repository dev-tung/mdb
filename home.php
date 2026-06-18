<?php require_once PATH_ROOT . 'header.php'; ?>
<?php require_once PATH_RETAIL . 'repository/product.php'; ?>
<?php
    $categories = get_categories();
    $featured_products = get_featured_products(6);
?>

<main class="container py-4">
  <!-- HERO / BANNER -->
  <section class="p-5 mb-5 text-white rounded-3 shadow-sm"
      style="
          background: linear-gradient(135deg, #2f3e2c 0%, #69a84f 55%, #3f6f32 100%);
      ">

      <div class="container-fluid py-3">

          <h1 class="display-5 fw-bold mb-3">
              Badminton Retail - Vợt Cầu Lông Chính Hãng
          </h1>

          <p class="col-lg-8 fs-5 opacity-75">
              Chuyên cung cấp vợt cầu lông, giày cầu lông, túi vợt và phụ kiện chính hãng Yonex, Victor, Lining với giá tốt và bảo hành đầy đủ.
          </p>

          <a href="/product" class="btn btn-light btn-lg fw-semibold"
            style="color:#2f3e2c;">
              Mua Ngay
          </a>

      </div>

  </section>

    <!-- DANH MỤC -->
    <section class="mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold mb-0">
                Danh mục sản phẩm
            </h2>

            <a href="/product" class="text-decoration-none text-success">
                Xem tất cả →
            </a>

        </div>

        <div class="row g-4">

            <?php foreach ($categories as $category): ?>

                <?php
                $img = !empty($category['thumbnail'])
                    ? URL_ROOT . '/retail/' . ltrim($category['thumbnail'], '/')
                    : 'https://placehold.co/600x400?text=' . urlencode($category['name']);
                ?>

                <div class="col-6 col-md-4 col-lg-3">

                    <a
                        href="/product?category=<?= urlencode($category['id']) ?>"
                        class="text-decoration-none text-dark">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="ratio ratio-4x3 bg-light">

                                <img
                                    src="<?= htmlspecialchars($img) ?>"
                                    alt="<?= htmlspecialchars($category['name']) ?>"
                                    class="w-100 h-100"
                                    style="object-fit:contain;padding:20px;">

                            </div>

                            <div class="card-body text-center">

                                <h6 class="mb-0 fw-semibold">

                                    <?= htmlspecialchars($category['name']) ?>

                                </h6>

                            </div>

                        </div>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </section>

<!-- SẢN PHẨM NỔI BẬT -->
<section class="mb-5">

    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 fs-3">Sản phẩm nổi bật</h2>
        <a href="/product" class="text-decoration-none text-success fw-semibold">
            Xem tất cả →
        </a>
    </header>

    <div class="row g-3 g-md-4">

        <?php foreach ($featured_products as $p): ?>

            <?php
                $img = !empty($p['thumbnail'])
                    ? (str_starts_with($p['thumbnail'], 'http')
                        ? $p['thumbnail']
                        : URL_ROOT . '/retail/' . ltrim($p['thumbnail'], '/'))
                    : 'https://placehold.co';

                $price = (int)($p['price'] ?? 0);
            ?>

            <div class="col-6 col-md-4 col-lg-2">

                <article class="card h-100 shadow-sm border-0 position-relative overflow-hidden">

                    <!-- Khung chứa ảnh: Dùng bg-light (hoặc bg-white) làm nền cho khoảng trống -->
                    <div class="w-100 bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <img
                            src="<?= htmlspecialchars($img) ?>"
                            class="w-100 h-100"
                            alt="<?= htmlspecialchars($p['name']) ?>"
                            style="object-fit: contain;"
                            loading="lazy">
                    </div>

                    <div class="card-body d-flex flex-column p-3">

                        <h6 class="card-title mb-2 fs-6 text-truncate">
                            <a href="/product/<?= urlencode($p['slug']) ?>" 
                               class="text-decoration-none text-dark stretched-link">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                        </h6>

                        <div class="text-danger fw-bold mt-auto pt-1">
                            <?= $price ? number_format($price) . '₫' : 'Liên hệ' ?>
                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

    </div>

</section>


  <!-- GIỚI THIỆU SEO -->
  <section class="bg-light rounded-3 p-5">

      <h2 class="fw-bold mb-4">
          Cửa Hàng Cầu Lông Chính Hãng Tại Việt Nam
      </h2>

      <p>
          Badminton Retail chuyên cung cấp các dòng vợt cầu lông chính hãng từ Yonex, Victor, Lining cùng nhiều thương hiệu nổi tiếng khác. Chúng tôi mang đến giải pháp mua sắm cầu lông toàn diện với đầy đủ vợt, giày, túi và phụ kiện.
      </p>

      <p>
          Tất cả sản phẩm đều được kiểm tra kỹ trước khi giao hàng, đảm bảo chất lượng và chính sách bảo hành rõ ràng. Với đội ngũ tư vấn giàu kinh nghiệm, khách hàng sẽ dễ dàng lựa chọn sản phẩm phù hợp với trình độ và phong cách thi đấu.
      </p>

  </section>

</main>

<?php require_once PATH_ROOT . 'footer.php'; ?>
