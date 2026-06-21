<main class="container py-4">

    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="bg-white border rounded p-3 text-center">

                <img id="mainImg"
                     src="https://placehold.co/600x600"
                     class="img-fluid rounded"
                     style="max-height:500px; object-fit:contain;"
                     alt="Product name">

            </div>

            <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">

                <img src="https://placehold.co/100x100"
                     class="border rounded"
                     style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                     onclick="document.getElementById('mainImg').src=this.src">

                <img src="https://placehold.co/100x100"
                     class="border rounded"
                     style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                     onclick="document.getElementById('mainImg').src=this.src">

                <img src="https://placehold.co/100x100"
                     class="border rounded"
                     style="width:60px;height:60px;object-fit:cover;cursor:pointer"
                     onclick="document.getElementById('mainImg').src=this.src">

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <div class="text-uppercase text-muted small mb-2">
                Category name
            </div>

            <h1 class="fw-bold mb-3">
                Product name
            </h1>

            <p class="text-secondary mb-4">
                Product description goes here. This is a sample description text.
            </p>

            <!-- PRICE -->
            <div class="mb-4">

                <div class="fs-3 fw-bold text-success">
                    1.234.567 ₫
                </div>

                <!-- hoặc dùng khi không có giá -->
                <!--
                <span class="badge bg-danger px-3 py-2 fs-6">
                    Liên hệ
                </span>
                -->

            </div>

            <!-- SPECIFICATIONS -->
            <div class="border rounded p-3 bg-light">

                <div class="fw-bold mb-3">Đặc điểm chi tiết</div>

                <table class="table table-sm mb-0">

                    <tr>
                        <th class="text-muted fw-normal">Thương hiệu</th>
                        <td class="fw-semibold">Yonex</td>
                    </tr>

                    <tr>
                        <th class="text-muted fw-normal">Trọng lượng</th>
                        <td class="fw-semibold">4U</td>
                    </tr>

                    <tr>
                        <th class="text-muted fw-normal">Độ cứng</th>
                        <td class="fw-semibold">Medium</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</main>