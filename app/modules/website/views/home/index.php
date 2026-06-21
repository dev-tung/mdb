<main class="container py-4">

    <div id="heroBanner" class="alert alert-dismissible fade show p-0 border-0 mb-5">

        <section
            class="p-5 text-white rounded-3 shadow-sm position-relative"
            style="
                background: linear-gradient(
                    135deg,
                    #2f3e2c 0%,
                    #69a84f 55%,
                    #3f6f32 100%
                );
            ">

            <button
                type="button"
                class="btn-close btn-close-white position-absolute top-0 end-0 shadow-none p-2"
                style="transform: scale(.7);"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

            <div class="container-fluid py-3">

                <h1 class="display-5 fw-bold mb-3">
                    Badminton Shop - Vợt Cầu Lông Chính Hãng
                </h1>

                <p class="col-lg-8 fs-5 opacity-75">
                    Chuyên cung cấp vợt cầu lông, giày cầu lông, túi vợt và phụ kiện chính hãng Yonex, Victor, Lining với giá tốt và bảo hành đầy đủ.
                </p>

                <a
                    href="/product"
                    class="btn btn-light btn-lg fw-semibold"
                    style="color:#2f3e2c;">

                    Mua Ngay

                </a>

            </div>

        </section>

    </div>

<!-- DANH MỤC -->
<section class="mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            Danh mục sản phẩm
        </h2>

        <a href="<?= route('product') ?>"
           class="text-decoration-none text-success">
            Xem tất cả →
        </a>

    </div>

    <div class="row g-4">

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=1"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/racket.png') ?>"
                            alt="Vợt cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Vợt cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=2"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/string.png') ?>"
                            alt="Cước cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Cước cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=3"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/machine.png') ?>"
                            alt="Máy đan vợt"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Máy đan vợt
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=4"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/shuttlecock.png') ?>"
                            alt="Quả cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Quả cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=5"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/clothes.png') ?>"
                            alt="Quần áo cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Quần áo cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=6"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/shoes.png') ?>"
                            alt="Giày cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Giày cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=7"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/bag.png') ?>"
                            alt="Túi cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Túi cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-6 col-md-4 col-lg-3">

            <a href="<?= route('product') ?>?category=8"
               class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm h-100">

                    <div class="ratio ratio-4x3 bg-light">

                        <img
                            src="<?= asset('website/image/category/accessory.png') ?>"
                            alt="Phụ kiện cầu lông"
                            class="w-100 h-100"
                            style="object-fit:contain;padding:20px;">

                    </div>

                    <div class="card-body text-center">

                        <h6 class="mb-0 fw-semibold">
                            Phụ kiện cầu lông
                        </h6>

                    </div>

                </div>

            </a>

        </div>

    </div>

</section>

<!-- SẢN PHẨM NỔI BẬT -->
<section class="mb-5">

    <header class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0 fs-3">
            Sản phẩm nổi bật
        </h2>

        <a href="<?= route('product') ?>"
           class="text-decoration-none text-success fw-semibold">
            Xem tất cả →
        </a>

    </header>

    <div class="row g-3 g-md-4">

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/astrox-88d-pro.jpg') ?>"
                        alt="Yonex Astrox 88D Pro"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/yonex-astrox-88d-pro') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Yonex Astrox 88D Pro

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        4.990.000₫
                    </div>

                </div>

            </article>

        </div>

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/axforce-100.jpg') ?>"
                        alt="Lining Axforce 100"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/lining-axforce-100') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Lining Axforce 100

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        5.290.000₫
                    </div>

                </div>

            </article>

        </div>

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/thruster-f-ultra.jpg') ?>"
                        alt="Victor Thruster F Ultra"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/victor-thruster-f-ultra') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Victor Thruster F Ultra

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        4.790.000₫
                    </div>

                </div>

            </article>

        </div>

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/aeronaut-9000.jpg') ?>"
                        alt="Lining Aeronaut 9000"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/lining-aeronaut-9000') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Lining Aeronaut 9000

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        3.990.000₫
                    </div>

                </div>

            </article>

        </div>

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/arcsaber-11-pro.jpg') ?>"
                        alt="Yonex Arcsaber 11 Pro"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/yonex-arcsaber-11-pro') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Yonex Arcsaber 11 Pro

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        5.190.000₫
                    </div>

                </div>

            </article>

        </div>

        <div class="col-6 col-md-4 col-lg-2">

            <article class="card h-100 shadow border position-relative overflow-hidden">

                <div
                    class="w-100 bg-light d-flex align-items-center justify-content-center"
                    style="height:180px;">

                    <img
                        src="<?= asset('website/image/product/nanoray-light.jpg') ?>"
                        alt="Yonex Nanoray Light"
                        class="w-100 h-100 p-2"
                        style="object-fit:contain;"
                        loading="lazy">

                </div>

                <div class="card-body d-flex flex-column p-3">

                    <h6 class="card-title mb-2 fs-6">

                        <a
                            href="<?= route('product/yonex-nanoray-light') ?>"
                            class="text-decoration-none text-dark stretched-link">

                            Yonex Nanoray Light

                        </a>

                    </h6>

                    <div class="text-danger fw-bold mt-auto">
                        1.590.000₫
                    </div>

                </div>

            </article>

        </div>

    </div>

</section>

    <!-- GIỚI THIỆU SEO -->
    <div class="alert alert-dismissible fade show p-0 border-0 mb-0">

        <section class="bg-light border rounded-3 p-5 position-relative">

            <button
                type="button"
                class="btn-close btn-sm position-absolute top-0 end-0 shadow-none p-2"
                style="transform: scale(.7);"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

            <h2 class="fw-bold mb-4">
                Cửa Hàng Cầu Lông Chính Hãng Tại Việt Nam
            </h2>

            <p>
                Badminton Shop chuyên cung cấp các dòng vợt cầu lông chính hãng từ Yonex, Victor, Lining cùng nhiều thương hiệu nổi tiếng khác. Chúng tôi mang đến giải pháp mua sắm cầu lông toàn diện với đầy đủ vợt, giày, túi và phụ kiện.
            </p>

            <p class="mb-0">
                Tất cả sản phẩm đều được kiểm tra kỹ trước khi giao hàng, đảm bảo chất lượng và chính sách bảo hành rõ ràng. Với đội ngũ tư vấn giàu kinh nghiệm, khách hàng sẽ dễ dàng lựa chọn sản phẩm phù hợp với trình độ và phong cách thi đấu.
            </p>

        </section>

    </div>

</main>