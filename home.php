<?php require_once ROOT_PATH . 'header.php'; ?>

<main class="container py-4">
  <!-- HERO / BANNER -->
  <section class="p-5 mb-5 text-white rounded-3 shadow-sm"
      style="
          background: linear-gradient(135deg, #2f3e2c 0%, #69a84f 55%, #3f6f32 100%);
      ">

      <div class="container-fluid py-3">

          <h1 class="display-5 fw-bold mb-3">
              Badminton Shop - Vợt Cầu Lông Chính Hãng
          </h1>

          <p class="col-lg-8 fs-5 opacity-75">
              Chuyên cung cấp vợt cầu lông, giày cầu lông, túi vợt và phụ kiện chính hãng Yonex, Victor, Lining với giá tốt và bảo hành đầy đủ.
          </p>

          <a href="/products" class="btn btn-light btn-lg fw-semibold"
            style="color:#2f3e2c;">
              Mua Ngay
          </a>

      </div>

  </section>

  <!-- DANH MỤC -->
  <section class="mb-5">

      <header class="mb-4">
          <h2 class="fw-bold">
              Danh Mục Sản Phẩm
          </h2>
      </header>

      <div class="row g-4">

          <div class="col-6 col-lg-3">
              <article class="card h-100 shadow-sm border-0">
                  <div class="card-body text-center">

                      <svg width="50" height="50" fill="currentColor" class="text-success mb-3" viewBox="0 0 16 16">
                          <path d="M5.5 2a.5.5 0 0 1 .5.5V7h4V2.5a.5.5 0 0 1 1 0V7h1.5a.5.5 0 0 1 0 1H11v5.5a.5.5 0 0 1-1 0V8H6v5.5a.5.5 0 0 1-1 0V8H3.5a.5.5 0 0 1 0-1H5V2.5a.5.5 0 0 1 .5-.5z"/>
                      </svg>

                      <h3 class="h5">Vợt Cầu Lông</h3>

                      <p class="text-muted small mb-0">
                          Vợt Yonex, Victor, Lining chính hãng.
                      </p>

                  </div>
              </article>
          </div>

          <div class="col-6 col-lg-3">
              <article class="card h-100 shadow-sm border-0">
                  <div class="card-body text-center">

                      <svg width="50" height="50" fill="currentColor" class="text-success mb-3" viewBox="0 0 16 16">
                          <path d="M8 0a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2V2a2 2 0 0 1 2-2z"/>
                      </svg>

                      <h3 class="h5">Giày Cầu Lông</h3>

                      <p class="text-muted small mb-0">
                          Êm ái, chống trượt và bảo vệ cổ chân.
                      </p>

                  </div>
              </article>
          </div>

          <div class="col-6 col-lg-3">
              <article class="card h-100 shadow-sm border-0">
                  <div class="card-body text-center">

                      <svg width="50" height="50" fill="currentColor" class="text-success mb-3" viewBox="0 0 16 16">
                          <path d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                      </svg>

                      <h3 class="h5">Túi Vợt</h3>

                      <p class="text-muted small mb-0">
                          Nhiều ngăn tiện lợi, chống thấm nước.
                      </p>

                  </div>
              </article>
          </div>

          <div class="col-6 col-lg-3">
              <article class="card h-100 shadow-sm border-0">
                  <div class="card-body text-center">

                      <svg width="50" height="50" fill="currentColor" class="text-success mb-3" viewBox="0 0 16 16">
                          <path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1z"/>
                      </svg>

                      <h3 class="h5">Phụ Kiện</h3>

                      <p class="text-muted small mb-0">
                          Quấn cán, dây đan, tất và phụ kiện khác.
                      </p>

                  </div>
              </article>
          </div>

      </div>

  </section>

  <!-- SẢN PHẨM NỔI BẬT -->
  <section class="mb-5">

      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fw-bold mb-0">
              Sản Phẩm Nổi Bật
          </h2>

          <a href="/products" class="btn btn-outline-success">
              Xem Tất Cả
          </a>
      </header>

      <div class="row g-4">

          <?php for ($i = 1; $i <= 8; $i++): ?>

              <div class="col-6 col-lg-3">

                  <article class="card h-100 shadow-sm">

                      <div class="card-body">

                          <span class="badge bg-success mb-2">
                              HOT
                          </span>

                          <h3 class="h5">
                              Yonex Astrox <?= $i ?>
                          </h3>

                          <p class="text-muted small">
                              Vợt cầu lông cao cấp dành cho người chơi phong trào và thi đấu.
                          </p>

                          <p class="h5 text-danger fw-bold mb-0">
                              <?= number_format(1990000 + ($i * 100000), 0, ',', '.') ?>đ
                          </p>

                      </div>

                      <div class="card-footer bg-white border-0">
                          <a href="#" class="btn btn-success w-100">
                              Xem Chi Tiết
                          </a>
                      </div>

                  </article>

              </div>

          <?php endfor; ?>

      </div>

  </section>

  <!-- GIỚI THIỆU SEO -->
  <section class="bg-light rounded-3 p-5">

      <h2 class="fw-bold mb-4">
          Cửa Hàng Cầu Lông Chính Hãng Tại Việt Nam
      </h2>

      <p>
          Badminton Shop chuyên cung cấp các dòng vợt cầu lông chính hãng từ Yonex, Victor, Lining cùng nhiều thương hiệu nổi tiếng khác. Chúng tôi mang đến giải pháp mua sắm cầu lông toàn diện với đầy đủ vợt, giày, túi và phụ kiện.
      </p>

      <p>
          Tất cả sản phẩm đều được kiểm tra kỹ trước khi giao hàng, đảm bảo chất lượng và chính sách bảo hành rõ ràng. Với đội ngũ tư vấn giàu kinh nghiệm, khách hàng sẽ dễ dàng lựa chọn sản phẩm phù hợp với trình độ và phong cách thi đấu.
      </p>

  </section>

</main>

<?php require_once ROOT_PATH . 'footer.php'; ?>
