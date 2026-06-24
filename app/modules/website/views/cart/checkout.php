<main class="container py-4">

    <div class="row g-4">

        <!-- LEFT: PRODUCTS -->
        <div class="col-12 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Đơn hàng của bạn</h5>
                </div>

                <div class="card-body">

                    <div id="checkout-items">
                        Đang tải...
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Tổng tiền</span>
                        <span id="checkout-total">0 ₫</span>
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT: CUSTOMER INFO -->
        <div class="col-12 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Thông tin nhận hàng</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" id="customer_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" id="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" id="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea id="note" class="form-control" rows="3"></textarea>
                    </div>

                    <button class="btn btn-success w-100"
                            onclick="submitOrder()">
                        Đặt hàng
                    </button>

                </div>

            </div>

        </div>

    </div>

</main>

<script>
function getCart() {

    return JSON.parse(sessionStorage.getItem('buy_now'))
        || JSON.parse(localStorage.getItem('cart'))
        || [];
}

function renderCheckout() {

    const cart = getCart();
    const container = document.getElementById('checkout-items');

    if (!cart.length) {
        container.innerHTML = `<div class="alert alert-warning">Giỏ hàng trống</div>`;
        return;
    }

    let total = 0;
    container.innerHTML = '';

    cart.forEach(item => {

        total += item.price * item.quantity;

        container.innerHTML += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                <div class="d-flex align-items-center gap-2">

                    <img src="${item.image}" width="50" height="50" style="object-fit:contain">

                    <div>
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">x${item.quantity}</small>
                    </div>

                </div>

                <div class="fw-bold text-danger">
                    ${(item.price * item.quantity).toLocaleString('vi-VN')} ₫
                </div>

            </div>
        `;
    });

    document.getElementById('checkout-total').innerText =
        total.toLocaleString('vi-VN') + ' ₫';
}

async function submitOrder() {

    const cart = getCart();

    if (!cart.length) {
        alert('Giỏ hàng trống');
        return;
    }

    const payload = {
        customer_name: document.getElementById('customer_name').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        note: document.getElementById('note').value,
        items: cart
    };

    if (!payload.customer_name || !payload.phone || !payload.address) {
        alert('Vui lòng nhập đầy đủ thông tin');
        return;
    }

    try {

        const res = await fetch('/api/orders/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const json = await res.json();

        if (json.success) {

            sessionStorage.removeItem('buy_now');
            localStorage.removeItem('cart');

            alert('Đặt hàng thành công');

            window.location.href = '/order/success/' + json.order_id;

        } else {
            alert(json.message || 'Đặt hàng thất bại');
        }

    } catch (err) {
        console.error(err);
        alert('Lỗi hệ thống');
    }
}

document.addEventListener('DOMContentLoaded', renderCheckout);
</script>