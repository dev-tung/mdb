<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">
        Thêm đơn hàng
    </h3>

    <form id="export-add-form" novalidate>

        <div class="row g-3">

            <div class="col-md-6 position-relative">

                <label class="form-label">
                    Khách hàng
                </label>

                <input
                    type="text"
                    id="customer_search"
                    class="form-control"
                    placeholder="Tìm khách hàng..."
                    autocomplete="off">

                <input
                    type="hidden"
                    id="customer_id">

                <div
                    id="customer_suggestions"
                    class="list-group position-absolute w-100 d-none">
                </div>

                <small
                    id="error-customer"
                    class="text-danger d-none">
                </small>

            </div>

            <div class="col-md-6">

                <label
                    for="description"
                    class="form-label">

                    Mô tả

                </label>

                <input
                    type="text"
                    id="description"
                    class="form-control"
                    placeholder="Nhập mô tả đơn hàng">

            </div>

            <div class="col-md-6">

                <label
                    for="status"
                    class="form-label">

                    Trạng thái đơn hàng

                </label>

                <select
                    id="status"
                    class="form-select">

                    <option value="new">
                        Mới
                    </option>

                    <option value="processing">
                        Đang xử lý
                    </option>

                    <option value="completed">
                        Hoàn thành
                    </option>

                </select>

            </div>

            <div class="col-md-6">

                <label
                    for="payment_status"
                    class="form-label">

                    Trạng thái thanh toán

                </label>

                <select
                    id="payment_status"
                    class="form-select">

                    <option value="unpaid">
                        Chưa thanh toán
                    </option>

                    <option value="paid">
                        Đã thanh toán
                    </option>

                </select>

            </div>

            <div class="col-12 position-relative mt-4">

                <label class="form-label">
                    Sản phẩm
                </label>

                <input
                    type="text"
                    id="product_search"
                    class="form-control"
                    placeholder="Tìm sản phẩm..."
                    autocomplete="off">

                <div
                    id="product_suggestions"
                    class="list-group position-absolute w-100 d-none">
                </div>

            </div>

            <div class="col-12">

                <div class="border rounded p-3">

                    <div class="table-responsive">

                        <table class="table table-sm align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Tên
                                </th>

                                <th>
                                    SL
                                </th>

                                <th>
                                    Giá
                                </th>

                                <th>
                                    Giảm giá
                                </th>

                                <th>
                                    Quà tặng
                                </th>

                                <th>
                                    Thành tiền
                                </th>

                                <th>
                                    Hành động
                                </th>

                            </tr>

                        </thead>

                        <tbody id="selected_products">

                            <tr>

                                <td>
                                    Yonex Astrox 100ZZ
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        value="1"
                                        class="form-control form-control-sm">
                                </td>

                                <td>
                                    5.200.000
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        value="0"
                                        class="form-control form-control-sm">
                                </td>

                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input">
                                </td>

                                <td>
                                    5.200.000
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary">
                                        Xóa
                                    </button>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>
                <div class="col-12 text-start mt-3">

                    <h5 class="mb-0">
                        Tổng tiền
                        <span id="total_amount">
                            5.200.000
                        </span>
                        ₫
                    </h5>

                </div>
            </div>



            <div class="col-12">

                <button
                    type="submit"
                    class="btn btn-outline-secondary mt-3">

                    Thêm đơn hàng

                </button>

            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const customers = [
        {
            id: 1,
            name: "Nguyễn Văn A",
            group_name: "Bán lẻ",
            address: "Hà Nội"
        },
        {
            id: 2,
            name: "Trần Thị B",
            group_name: "CLB Cầu lông",
            address: "Hải Phòng"
        },
        {
            id: 3,
            name: "Lê Văn C",
            group_name: "Đại lý",
            address: "Nam Định"
        }
    ];

    const products = [
        {
            id: 1,
            name: "Yonex Astrox 100ZZ",
            price: 5200000,
            quantity: 5
        },
        {
            id: 2,
            name: "Yonex Nanoflare 1000Z",
            price: 4800000,
            quantity: 8
        },
        {
            id: 3,
            name: "Victor Thruster F",
            price: 3900000,
            quantity: 12
        }
    ];

    const customerInput = document.getElementById("customer_search");
    const customerId = document.getElementById("customer_id");
    const customerSuggestions = document.getElementById("customer_suggestions");

    const productInput = document.getElementById("product_search");
    const productSuggestions = document.getElementById("product_suggestions");

    const selectedProductsBody =
        document.getElementById("selected_products");

    const totalAmount =
        document.getElementById("total_amount");

    let selectedProducts = {};

    function formatMoney(value) {
        return Number(value).toLocaleString("vi-VN");
    }

    customerInput.addEventListener("input", function () {

        const keyword =
            this.value.toLowerCase().trim();

        customerSuggestions.innerHTML = "";

        customerId.value = "";

        if (!keyword) {
            customerSuggestions.classList.add("d-none");
            return;
        }

        const matches = customers.filter(customer =>
            customer.name.toLowerCase().includes(keyword)
        );

        if (!matches.length) {
            customerSuggestions.classList.add("d-none");
            return;
        }

        matches.forEach(customer => {

            const button =
                document.createElement("button");

            button.type = "button";

            button.className =
                "list-group-item list-group-item-action";

            button.textContent =
                `${customer.name} - ${customer.group_name} - ${customer.address}`;

            button.addEventListener("click", function () {

                customerInput.value =
                    `${customer.name} - ${customer.group_name}`;

                customerId.value =
                    customer.id;

                customerSuggestions.classList.add("d-none");

            });

            customerSuggestions.appendChild(button);

        });

        customerSuggestions.classList.remove("d-none");

    });

    productInput.addEventListener("input", function () {

        const keyword =
            this.value.toLowerCase().trim();

        productSuggestions.innerHTML = "";

        if (!keyword) {
            productSuggestions.classList.add("d-none");
            return;
        }

        const matches = products.filter(product =>
            product.name.toLowerCase().includes(keyword)
        );

        if (!matches.length) {
            productSuggestions.classList.add("d-none");
            return;
        }

        matches.forEach(product => {

            const button =
                document.createElement("button");

            button.type = "button";

            button.className =
                "list-group-item list-group-item-action";

            button.textContent =
                `${product.name} - ${formatMoney(product.price)} ₫ - Tồn ${product.quantity}`;

            button.addEventListener("click", function () {

                addProduct(product);

                productInput.value = "";

                productSuggestions.classList.add("d-none");

            });

            productSuggestions.appendChild(button);

        });

        productSuggestions.classList.remove("d-none");

    });

    function addProduct(product) {

        if (selectedProducts[product.id]) {
            return;
        }

        selectedProducts[product.id] = {
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1,
            discount: 0,
            is_gift: false,
            max_quantity: product.quantity
        };

        renderProducts();

    }

    function renderProducts() {

        selectedProductsBody.innerHTML = "";

        Object.values(selectedProducts).forEach(product => {

            const row =
                document.createElement("tr");

            row.innerHTML = `
                <td>${product.name}</td>

                <td>
                    <input
                        type="number"
                        min="1"
                        value="${product.quantity}"
                        data-id="${product.id}"
                        class="form-control form-control-sm qty-input">
                </td>

                <td>
                    ${formatMoney(product.price)}
                </td>

                <td>
                    <input
                        type="number"
                        min="0"
                        value="${product.discount}"
                        data-id="${product.id}"
                        class="form-control form-control-sm discount-input">
                </td>

                <td>
                    <input
                        type="checkbox"
                        data-id="${product.id}"
                        class="form-check-input gift-checkbox"
                        ${product.is_gift ? "checked" : ""}>
                </td>

                <td>
                    <span
                        class="item-total"
                        data-id="${product.id}">
                    </span>
                </td>

                <td>
                    <button
                        type="button"
                        data-id="${product.id}"
                        class="btn btn-sm btn-outline-secondary remove-btn">

                        Xóa

                    </button>
                </td>
            `;

            selectedProductsBody.appendChild(row);

        });

        bindEvents();

        calculateTotal();

    }

    function bindEvents() {

        document.querySelectorAll(".qty-input")
            .forEach(input => {

                input.addEventListener("input", function () {

                    const id = this.dataset.id;

                    let quantity =
                        parseInt(this.value) || 1;

                    const max =
                        selectedProducts[id].max_quantity;

                    if (quantity > max) {

                        quantity = max;

                        this.value = max;

                    }

                    selectedProducts[id].quantity =
                        quantity;

                    calculateTotal();

                });

            });

        document.querySelectorAll(".discount-input")
            .forEach(input => {

                input.addEventListener("input", function () {

                    const id = this.dataset.id;

                    selectedProducts[id].discount =
                        parseInt(this.value) || 0;

                    calculateTotal();

                });

            });

        document.querySelectorAll(".gift-checkbox")
            .forEach(input => {

                input.addEventListener("change", function () {

                    const id = this.dataset.id;

                    selectedProducts[id].is_gift =
                        this.checked;

                    calculateTotal();

                });

            });

        document.querySelectorAll(".remove-btn")
            .forEach(button => {

                button.addEventListener("click", function () {

                    delete selectedProducts[
                        this.dataset.id
                    ];

                    renderProducts();

                });

            });

    }

    function calculateTotal() {

        let total = 0;

        Object.values(selectedProducts).forEach(product => {

            let itemTotal = 0;

            if (!product.is_gift) {

                itemTotal =
                    Math.max(
                        product.price - product.discount,
                        0
                    ) * product.quantity;

            }

            const target =
                document.querySelector(
                    `.item-total[data-id="${product.id}"]`
                );

            if (target) {

                target.textContent =
                    formatMoney(itemTotal);

            }

            total += itemTotal;

        });

        totalAmount.textContent =
            formatMoney(total);

    }

    document.addEventListener("click", function (event) {

        if (
            !customerInput.contains(event.target) &&
            !customerSuggestions.contains(event.target)
        ) {
            customerSuggestions.classList.add("d-none");
        }

        if (
            !productInput.contains(event.target) &&
            !productSuggestions.contains(event.target)
        ) {
            productSuggestions.classList.add("d-none");
        }

    });

    document
        .getElementById("export-add-form")
        .addEventListener("submit", function (event) {

            event.preventDefault();

            const payload = {

                customer_id:
                    customerId.value,

                description:
                    document.getElementById("description").value,

                status:
                    document.getElementById("status").value,

                payment_status:
                    document.getElementById("payment_status").value,

                products:
                    Object.values(selectedProducts)

            };

            console.log(payload);

            alert(
                "Fake submit thành công. Kiểm tra Console."
            );

        });

});
</script>