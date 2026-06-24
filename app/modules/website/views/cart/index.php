<main class="container py-4">

    <div class="row g-4">

        <!-- CART LIST -->
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Giỏ hàng</h5>
                </div>

                <div class="card-body">

                    <div id="cart-items">
                        Đang tải...
                    </div>

                </div>

            </div>

        </div>

        <!-- SUMMARY -->
        <div class="col-12 col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Tổng đơn hàng</h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính</span>
                        <span id="cart-total">0 ₫</span>
                    </div>

                    <button class="btn btn-success w-100 mb-2"
                            onclick="goCheckout()">
                        Thanh toán
                    </button>

                    <a href="/product" class="btn btn-outline-secondary w-100">
                        Tiếp tục mua hàng
                    </a>

                </div>

            </div>

        </div>

    </div>

</main>

<script>

function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function renderCart() {

    const cart = getCart();
    const container = document.getElementById('cart-items');

    if (!cart.length) {
        container.innerHTML = `
            <div class="alert alert-warning">
                Giỏ hàng trống
            </div>
        `;
        document.getElementById('cart-total').innerText = '0 ₫';
        return;
    }

    let total = 0;
    container.innerHTML = '';

    cart.forEach((item, index) => {

        total += item.price * item.quantity;

        container.innerHTML += `
            <div class="d-flex align-items-center justify-content-between border-bottom py-3">

                <div class="d-flex align-items-center gap-3">

                    <img src="${item.image}"
                         width="60"
                         height="60"
                         style="object-fit:contain">

                    <div>
                        <div class="fw-semibold">${item.name}</div>
                        <div class="text-danger fw-bold">
                            ${item.price.toLocaleString('vi-VN')} ₫
                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <input type="number"
                           min="1"
                           value="${item.quantity}"
                           class="form-control form-control-sm"
                           style="width:70px"
                           onchange="updateQty(${index}, this.value)">

                    <button class="btn btn-outline-danger btn-sm"
                            onclick="removeItem(${index})">
                        Xóa
                    </button>

                </div>

            </div>
        `;
    });

    document.getElementById('cart-total').innerText =
        total.toLocaleString('vi-VN') + ' ₫';
}

function updateQty(index, qty) {

    let cart = getCart();
    qty = parseInt(qty);

    if (qty < 1) qty = 1;

    cart[index].quantity = qty;

    saveCart(cart);
    renderCart();
}

function removeItem(index) {

    let cart = getCart();

    cart.splice(index, 1);

    saveCart(cart);
    renderCart();
}

function goCheckout() {
    window.location.href = '/checkout';
}

document.addEventListener('DOMContentLoaded', renderCart);

</script>