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

                        <select class="form-select form-select-sm"
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

                                <input class="form-check-input"
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

                        <?php $priceRanges = config('shop.option.price_range') ?? []; ?>

                        <?php foreach ($priceRanges as $key => $item): ?>

                        <div class="form-check small mb-1">

                            <input class="form-check-input"
                                   type="radio"
                                   name="price"
                                   id="price_<?= $key ?>"
                                   value="<?= $key ?>">

                            <label class="form-check-label"
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
                <ul class="pagination pagination-sm shadow-sm mb-0"
                    id="pagination"></ul>
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
                ...document.querySelectorAll('input[name="brand[]"]:checked')
            ].map(item => item.value);

            const price =
                document.querySelector('input[name="price"]:checked')?.value || '';

            const query = new URLSearchParams();

            query.append('page', page);

            if (keyword) query.append('keyword', keyword);
            if (category) query.append('category_id', category);
            if (price) query.append('price', price);

            brands.forEach(id => query.append('brand[]', id));

            const response = await fetch(`/api/products/stock?${query.toString()}`);
            const json = await response.json();

            const container = document.getElementById('product-list');
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


                container.innerHTML += `
                    <div class="col-6 col-md-4 col-xl-3">

                        <div class="card h-100 border-0 shadow-sm d-flex flex-column">

                            <a href="${url}" class="text-decoration-none text-dark">

                                <div class="ratio ratio-1x1 bg-light">

                                    <img src="${image}"
                                         alt="${product.name}"
                                         class="w-100 h-100 p-2"
                                         style="object-fit:contain">

                                </div>

                                <div class="card-body flex-grow-1">

                                    <h6 class="mb-2"
                                        style="
                                            display: -webkit-box;
                                            -webkit-line-clamp: 2;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            line-height: 1.3em;
                                            height: 2.6em;
                                        ">
                                        ${product.name}
                                    </h6>

                                    <div class="text-danger fw-bold">

                                        ${
                                            product.price > 0
                                            ? (
                                                product.sale_price && Number(product.sale_price) > 0
                                                ? `
                                                    <small class="text-muted text-decoration-line-through me-1">
                                                        ${Number(product.price).toLocaleString('vi-VN')} ₫
                                                    </small>
                                                    <span>
                                                        ${Number(product.sale_price).toLocaleString('vi-VN')} ₫
                                                    </span>
                                                `
                                                : `
                                                    <span>
                                                        ${Number(product.price).toLocaleString('vi-VN')} ₫
                                                    </span>
                                                `
                                            )
                                            : 'Tạm hết hàng'
                                        }

                                    </div>

                                </div>

                            </a>

                                <div class="p-2 pt-0">

                                    ${
                                        product.stock > 0
                                        ? `
                                            <button
                                                class="btn btn-outline-success btn-sm w-100"
                                                onclick="buyNow(
                                                    ${product.id},
                                                    '${product.name.replace(/'/g, "\\'")}',
                                                    ${price},
                                                    '${image}'
                                                )"
                                            >
                                                Mua hàng
                                            </button>
                                        `
                                        : `
                                            <a href="tel:0973359165" class="btn btn-outline-secondary btn-sm w-100">
                                                Liên hệ đặt hàng
                                            </a>
                                        `
                                    }

                                </div>

                        </div>

                    </div>
                `;
            });

            lastPage = json.meta?.totalPages || 1;
            prevPage = Math.max(1, page - 1);
            nextPage = Math.min(lastPage, page + 1);

            renderPagination(page, lastPage);

        } catch (e) {

            console.error(e);

            document.getElementById('product-list').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Lỗi tải dữ liệu sản phẩm
                    </div>
                </div>
            `;
        }
    }

    function renderPagination(page, totalPages) {

        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        let html = '';

        html += `
            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="1">Đầu</a>
            </li>

            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${prevPage}">‹</a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {

            if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {

                html += `
                    <li class="page-item ${i === page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">
                            ${i}
                        </a>
                    </li>
                `;
            }
        }

        html += `
            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${nextPage}">›</a>
            </li>

            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${lastPage}">Cuối</a>
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

        const link = e.target.closest('[data-page]');
        if (!link) return;

        e.preventDefault();

        const page = parseInt(link.dataset.page);
        if (!isNaN(page)) loadProducts(page);
    });

    loadProducts(1);
});

function buyNow(id, name, price, image) {

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // check product exists
    const index = cart.findIndex(item => item.product_id === id);

    if (index !== -1) {
        cart[index].quantity += 1;
    } else {
        cart.push({
            product_id: id,
            name,
            price,
            image,
            quantity: 1
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    // chuyển sang giỏ hàng
    window.location.href = '/cart';
}
</script>