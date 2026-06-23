<?php
$statuses = config('shop.option.order_status');
$payments = config('shop.option.payment');
?>

<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Tạo đơn hàng
    </h3>

    <form id="order-create-form" novalidate>

        <div class="row g-3">

            <!-- CUSTOMER -->
            <div class="col-md-6 position-relative">

                <label class="form-label">Khách hàng</label>

                <input type="text"
                       id="customer_search"
                       class="form-control"
                       placeholder="Tìm khách hàng..."
                       autocomplete="off">

                <input type="hidden" id="customer_id">

                <div id="customer_suggestions"
                     class="list-group position-absolute w-100 d-none z-1">
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-6">

                <label class="form-label">Mô tả</label>

                <input type="text"
                       id="description"
                       class="form-control"
                       placeholder="Nhập mô tả đơn hàng">

            </div>

            <!-- STATUS -->
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>

                <select id="status" class="form-select">
                    <?php foreach ($statuses as $key => $status): ?>
                        <option value="<?= $key ?>">
                            <?= $status['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PAYMENT STATUS -->
            <div class="col-md-3">
                <label class="form-label">Thanh toán</label>

                <select id="payment" class="form-select">
                    <?php foreach ($payments as $key => $payment): ?>
                        <option value="<?= $key ?>">
                            <?= $payment['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PRODUCT SEARCH -->
            <div class="col-12 position-relative mt-4">

                <label class="form-label">Sản phẩm</label>

                <input type="text"
                       id="product_search"
                       class="form-control"
                       placeholder="Tìm sản phẩm..."
                       autocomplete="off">

                <div id="product_suggestions"
                     class="list-group position-absolute w-100 d-none">
                </div>

            </div>

            <!-- TABLE -->
            <div class="col-12">

                <div class="border rounded p-3">

                    <div class="table-responsive">

                        <table class="table table-sm align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Giảm giá</th>
                                    <th>Thành tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody id="selected_products"></tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        <h5>
                            Tổng tiền:
                            <span id="total_amount">0</span> ₫
                        </h5>
                    </div>

                </div>

            </div>

            <!-- SUBMIT -->
            <div class="col-12">

                <button type="submit"
                        class="btn btn-outline-secondary mt-3">
                    Tạo đơn hàng
                </button>

            </div>

        </div>

    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ORDER_ID = "<?= $id ?? '' ?>";

    const API = {
        customers: "/api/customers",
        products: "/api/products",
        orders: ORDER_ID
            ? ("/api/orders/show/" + ORDER_ID)
            : "/api/orders"
    };

    let selectedProducts = {};
    let CUSTOMERS_MAP = {};

    const customerInput = document.getElementById("customer_search");
    const customerId = document.getElementById("customer_id");
    const customerBox = document.getElementById("customer_suggestions");

    const productInput = document.getElementById("product_search");
    const productBox = document.getElementById("product_suggestions");

    const tbody = document.getElementById("selected_products");
    const totalEl = document.getElementById("total_amount");

    function money(v) {
        return Number(v).toLocaleString("vi-VN");
    }

    // =========================
    // LOAD CUSTOMERS CACHE
    // =========================
    async function loadCustomersCache() {
        const res = await fetch(API.customers);
        const json = await res.json();
        const data = json.data || json;

        CUSTOMERS_MAP = {};

        data.forEach(c => {
            CUSTOMERS_MAP[c.id] = c.name || c.customer_name;
        });
    }

    // =========================
    // LOAD ORDER
    // =========================
    async function loadOrder() {

        if (!ORDER_ID) return;

        const res = await fetch(API.orders);
        const json = await res.json();
        const data = json.data;

        customerInput.value = CUSTOMERS_MAP[data.customer_id] || "";
        customerId.value = data.customer_id || "";

        document.getElementById("description").value = data.description || "";
        document.getElementById("status").value = data.status || "";
        document.getElementById("payment").value = data.payment || "";

        selectedProducts = {};

        (data.products || []).forEach(p => {
            selectedProducts[p.product_id] = {
                id: p.product_id,
                name: p.name,
                price: Number(p.price),
                base_price: Number(p.price),
                quantity: Number(p.quantity),
                discount: Number(p.discount || 0),
                gift: false
            };
        });

        render();
    }

    // =========================
    // CUSTOMER SEARCH
    // =========================
    customerInput.addEventListener("input", async function () {

        const keyword = this.value.trim();

        customerBox.innerHTML = "";
        customerId.value = "";

        if (!keyword) {
            customerBox.classList.add("d-none");
            return;
        }

        const res = await fetch(`${API.customers}?keyword=${encodeURIComponent(keyword)}`);
        const json = await res.json();
        const data = json.data || [];

        if (!data.length) {
            customerBox.classList.add("d-none");
            return;
        }

        data.forEach(c => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "list-group-item list-group-item-action";
            btn.textContent = c.name;

            btn.onclick = () => {
                customerInput.value = c.name;
                customerId.value = c.id;
                customerBox.classList.add("d-none");
            };

            customerBox.appendChild(btn);
        });

        customerBox.classList.remove("d-none");
    });

    // =========================
    // PRODUCT SEARCH
    // =========================
    productInput.addEventListener("input", async function () {

        const keyword = this.value.trim();

        productBox.innerHTML = "";

        if (!keyword) {
            productBox.classList.add("d-none");
            return;
        }

        const res = await fetch(`${API.products}?keyword=${encodeURIComponent(keyword)}`);
        const json = await res.json();
        const data = json.data || [];

        if (!data.length) {
            productBox.classList.add("d-none");
            return;
        }

        data.forEach(p => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "list-group-item list-group-item-action";
            btn.textContent = p.name;

            btn.onclick = () => {
                addProduct(p);
                productInput.value = "";
                productBox.classList.add("d-none");
            };

            productBox.appendChild(btn);
        });

        productBox.classList.remove("d-none");
    });

    // =========================
    // ADD PRODUCT
    // =========================
    function addProduct(p) {

        if (selectedProducts[p.id]) return;

        selectedProducts[p.id] = {
            id: p.id,
            name: p.name,
            price: Number(p.sale_price || 0),
            base_price: Number(p.sale_price || 0),
            quantity: 1,
            discount: 0,
            gift: false
        };

        render();
    }

    // =========================
    // RENDER (SORT FIXED)
    // =========================
    function render() {

        tbody.innerHTML = "";

        Object.values(selectedProducts)
            .sort((a, b) => a.id - b.id)
            .forEach(p => {

                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${p.name}</td>

                    <td>
                        <input type="number"
                               min="1"
                               value="${p.quantity}"
                               data-id="${p.id}"
                               class="form-control form-control-sm qty">
                    </td>

                    <td>
                        <input type="number"
                               min="0"
                               value="${p.price}"
                               data-id="${p.id}"
                               class="form-control form-control-sm price"
                               ${p.gift ? 'disabled' : ''}>
                    </td>

                    <td>
                        <input type="number"
                               min="0"
                               value="${p.discount}"
                               data-id="${p.id}"
                               class="form-control form-control-sm discount">
                    </td>

                    <td>
                        <div class="form-check">
                            <input class="form-check-input gift"
                                   type="checkbox"
                                   data-id="${p.id}"
                                   ${p.gift ? 'checked' : ''}>
                            <label class="form-check-label">Quà tặng</label>
                        </div>
                    </td>

                    <td>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger remove"
                                data-id="${p.id}">
                            Xóa
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            });

        calc();
    }

    // =========================
    // EVENT DELEGATION (FIX ALL BUGS)
    // =========================

    // INPUT EVENTS
    tbody.addEventListener("input", function (e) {

        const id = e.target.dataset.id;
        if (!id || !selectedProducts[id]) return;

        const item = selectedProducts[id];

        if (e.target.classList.contains("qty")) {
            item.quantity = +e.target.value || 1;
        }

        if (e.target.classList.contains("price")) {
            item.price = +e.target.value || 0;
            item.base_price = item.price;
        }

        if (e.target.classList.contains("discount")) {
            item.discount = +e.target.value || 0;
        }

        calc();
    });

    // CHANGE EVENTS (GIFT + REMOVE)
    tbody.addEventListener("change", function (e) {

        const id = e.target.dataset.id;
        if (!id || !selectedProducts[id]) return;

        const item = selectedProducts[id];

        // GIFT TOGGLE
        if (e.target.classList.contains("gift")) {

            item.gift = e.target.checked;

            if (item.gift) {
                item.price = 0;
            } else {
                item.price = item.base_price;
            }

            render();
        }
    });

    // REMOVE BUTTON
    tbody.addEventListener("click", function (e) {

        const id = e.target.dataset.id;
        if (!id || !selectedProducts[id]) return;

        if (e.target.classList.contains("remove")) {
            delete selectedProducts[id];
            render();
        }
    });

    // =========================
    // CALC
    // =========================
    function calc() {

        let total = 0;

        Object.values(selectedProducts).forEach(p => {

            const sum = (p.price * p.quantity) - (p.discount || 0);

            const el = document.querySelector(`.item-total[data-id="${p.id}"]`);
            if (el) el.textContent = money(sum);

            total += sum;
        });

        totalEl.textContent = money(total);
    }

    // =========================
    // SUBMIT
    // =========================
    document.getElementById("order-create-form")
        .addEventListener("submit", async function (e) {

            e.preventDefault();

            const customer = customerId.value.trim();

            if (!customer) {
                alert("Vui lòng chọn khách hàng");
                return;
            }

            const products = Object.values(selectedProducts);

            if (!products.length) {
                alert("Vui lòng thêm sản phẩm");
                return;
            }

            const payload = {
                customer_id: customerId.value,
                description: document.getElementById("description").value,
                status: document.getElementById("status").value,
                payment: document.getElementById("payment").value,
                products: products
            };

            const url = ORDER_ID
                ? "/api/orders/update"
                : "/api/orders";

            const res = await fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                alert(data.message || "Lỗi");
                return;
            }

            alert("Tạo đơn hàng thành công");

            window.location.href = "/admin/orders";
        });

    // =========================
    // INIT
    // =========================
    loadCustomersCache().then(() => {
        loadOrder();
    });

});
</script>