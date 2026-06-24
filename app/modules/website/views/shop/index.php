<main class="container py-4">

    <div class="row g-4">

        <!-- FILTER -->
        <aside class="col-12 col-lg-3">

            <div class="position-sticky" style="top:20px;">

                <div class="border rounded bg-white shadow-sm p-3">

                    <h5 class="fw-bold mb-3 text-default">
                        Bộ lọc
                    </h5>

                    <!-- CATEGORY -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Danh mục
                        </label>

                        <select
                            class="form-select form-select-sm"
                            id="filter-category">

                            <option value="">
                                Tất cả danh mục
                            </option>

                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= $cat['name'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <hr class="my-3">

                    <!-- BRAND -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Thương hiệu
                        </label>

                        <?php foreach($brands as $brand): ?>
                            <div class="form-check small mb-1">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="brand[]"
                                    value="<?= $brand['id'] ?>">

                                <label class="form-check-label">
                                    <?= $brand['name'] ?>
                                </label>

                            </div>
                        <?php endforeach; ?>

                    </div>

                    <hr class="my-3">

                    <!-- PRICE -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Khoảng giá
                        </label>

                        <?php
                            $priceRanges = config('shop.option.price_range') ?? [];
                        ?>

                        <?php foreach ($priceRanges as $key => $item): ?>

                        <div class="form-check small mb-1">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="price"
                                id="price_<?= $key ?>"
                                value="<?= $key ?>">

                            <label
                                class="form-check-label"
                                for="price_<?= $key ?>">

                                <?= $item['label'] ?>

                            </label>

                        </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </aside>

        <!-- PRODUCTS -->
        <section class="col-12 col-lg-9">

            <div class="row g-3" id="product-list">

                <div class="col-12 text-center">
                    Đang tải...
                </div>

            </div>

            <!-- PAGINATION -->
            <nav class="mt-3 d-flex">
                <ul
                    class="pagination pagination-sm shadow-sm mb-0"
                    id="pagination">
                </ul>
            </nav>

        </section>

    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let currentPage = 1;
    let lastPage = 1;
    let prevPage = 1;
    let nextPage = 1;

    // LẤY KEYWORD TỪ URL
    function getKeywordFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('keyword') || '';
    }

    async function loadProducts(page = 1) {

        try {

            currentPage = page;

            const keyword = getKeywordFromUrl();

            const category =
                document.getElementById('filter-category')?.value || '';

            const brands = [
                ...document.querySelectorAll(
                    'input[name="brand[]"]:checked'
                )
            ].map(item => item.value);

            const price =
                document.querySelector(
                    'input[name="price"]:checked'
                )?.value || '';

            const query = new URLSearchParams();

            query.append('page', page);

            // 👉 ADD KEYWORD FROM URL
            if (keyword) {
                query.append('keyword', keyword);
            }

            if (category) {
                query.append('category_id', category);
            }

            if (price) {
                query.append('price', price);
            }

            brands.forEach(id => {
                query.append('brand[]', id);
            });

            const response = await fetch(
                `/api/products?${query.toString()}`
            );

            const json = await response.json();

            const container =
                document.getElementById('product-list');

            if (!container) return;

            container.innerHTML = '';

            if (!json.data || json.data.length === 0) {

                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-light border text-center">
                            Không có sản phẩm nào
                        </div>
                    </div>
                `;

                return;
            }

            json.data.forEach(product => {

                const image =
                    product.thumbnail ||
                    product.image ||
                    product.featured_image ||
                    '/assets/image/no-image.svg';

                const url =
                    product.slug
                        ? `/product/${product.slug}`
                        : `/product/${product.id}`;

                const price =
                    Number(
                        product.sale_price ||
                        product.price ||
                        0
                    );

                container.innerHTML += `
                    <div class="col-6 col-md-4 col-xl-3">

                        <a href="${url}"
                           class="text-decoration-none text-dark">

                            <div class="card h-100 border-0 shadow-sm">

                                <div class="ratio ratio-1x1 bg-light">

                                    <img
                                        src="${image}"
                                        alt="${product.name}"
                                        class="w-100 h-100 p-2"
                                        style="object-fit:contain">

                                </div>

                                <div class="card-body">

                                    <h6 class="mb-2">
                                        ${product.name}
                                    </h6>

                                    <div class="text-danger fw-bold">

                                        ${
                                            price > 0
                                            ? price.toLocaleString('vi-VN') + ' ₫'
                                            : 'Liên hệ'
                                        }

                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>
                `;
            });

            lastPage =
                json.meta?.totalPages ||
                json.meta?.lastPage ||
                json.meta?.total_pages ||
                1;

            prevPage = Math.max(1, page - 1);
            nextPage = Math.min(lastPage, page + 1);

            renderPagination(page, lastPage);

        } catch (error) {

            console.error(error);

            const container =
                document.getElementById('product-list');

            if (container) {

                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            Lỗi tải dữ liệu sản phẩm
                        </div>
                    </div>
                `;
            }
        }
    }

    function renderPagination(page, totalPages) {

        const pagination =
            document.getElementById('pagination');

        if (!pagination) return;

        let html = '';

        html += `
            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link"
                   href="#"
                   data-page="1">
                    Đầu
                </a>
            </li>

            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link"
                   href="#"
                   data-page="${prevPage}">
                    ‹
                </a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {

            if (
                i === 1 ||
                i === totalPages ||
                (i >= page - 2 && i <= page + 2)
            ) {

                html += `
                    <li class="page-item ${i === page ? 'active' : ''}">

                        <a class="page-link"
                           href="#"
                           data-page="${i}">
                            ${i}
                        </a>

                    </li>
                `;
            }
        }

        html += `
            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link"
                   href="#"
                   data-page="${nextPage}">
                    ›
                </a>
            </li>

            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link"
                   href="#"
                   data-page="${lastPage}">
                    Cuối
                </a>
            </li>
        `;

        pagination.innerHTML = html;
    }

    document.addEventListener('change', e => {

        if (
            e.target.id === 'filter-category' ||
            e.target.name === 'brand[]' ||
            e.target.name === 'price'
        ) {
            loadProducts(1);
        }

    });

    document.addEventListener('click', e => {

        const link =
            e.target.closest('[data-page]');

        if (!link) return;

        e.preventDefault();

        const page =
            parseInt(link.dataset.page);

        if (!isNaN(page)) {
            loadProducts(page);
        }
    });

    loadProducts(1);

});
</script>