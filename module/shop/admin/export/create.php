<?php require_once PATH_ROOT . 'navbar.php'; ?>
<div class="container-fluid py-4 mt-5">
  <div class="card shadow-sm w-100">
    <div class="card-body">
      <h3 class="card-title mb-4">Thêm đơn hàng</h3>

      <form id="export-add-form" novalidate>
        <div class="row g-3">

          <!-- Search khách hàng -->
          <div class="col-md-6 position-relative">
            <label class="form-label">Khách hàng</label>
            <input type="text" id="customer_search" class="form-control" placeholder="Tìm khách hàng..." autocomplete="off">
            <input type="hidden" id="customer_id">
            <div id="customer_suggestions"
              class="list-group position-absolute w-100 d-none"
              style="max-height:220px; overflow-y:auto; z-index:1050;">
            </div>
            <small class="text-danger d-none" id="error-customer"></small>
          </div>

          <!-- Description -->
          <div class="col-md-6">
            <label for="description" class="form-label">Mô tả</label>
            <input type="text" class="form-control" id="description" placeholder="Nhập mô tả đơn hàng">
            <small class="text-danger d-none" id="error-description"></small>
          </div>

          <!-- Status đơn hàng -->
          <div class="col-md-6">
            <label for="status" class="form-label">Trạng thái đơn hàng</label>
            <select id="status" class="form-select">

            </select>
          </div>

          <!-- Status thanh toán -->
          <div class="col-md-6">
            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
            <select id="payment_status" class="form-select">

            </select>
          </div>

          <!-- Search sản phẩm -->
          <div class="col-12 position-relative mt-4">
            <label class="form-label">Sản phẩm</label>
            <input type="text" id="product_search" class="form-control" placeholder="Tìm sản phẩm..." autocomplete="off">
            <div id="product_suggestions"
              class="list-group position-absolute w-100 d-none"
              style="max-height:250px; overflow-y:auto; z-index:1040;">
            </div>
          </div>

          <!-- DANH SÁCH SẢN PHẨM ĐÃ CHỌN -->
          <div class="col-12 mt-3">
            <div class="table-responsive rounded overflow-hidden">
              <table class="table table-sm table-striped table-borderless align-middle mb-0">
                <thead class="table-light">
                  <tr class="align-middle">
                    <th class="w-auto py-2">Sản phẩm</th>
                    <th class="w-auto py-2">SL</th>
                    <th class="w-auto py-2">Giá</th>
                    <th class="w-auto py-2">Giảm giá</th>
                    <th class="w-auto py-2 text-center">Quà tặng</th>
                    <th class="w-auto py-2">Thành tiền</th>
                    <th class="w-auto py-2 text-center">Hành động</th>
                  </tr>
                </thead>
                <tbody id="selected_products"></tbody>
              </table>
            </div>
          </div>

          <!-- Tổng tiền -->
          <div class="col-12 text-end">
            <h5>Tổng tiền <span id="total_amount">0</span> đ</h5>
          </div>

          <!-- Submit -->
          <div class="col-12">
            <button type="submit" class="btn btn-secondary mt-3">Thêm đơn hàng</button>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // --------------------------
  // LOAD KHÁCH HÀNG
  // --------------------------
  let allCustomers = [];
  fetch("/api/customer/list")
    .then(res => res.json())
    .then(json => { allCustomers = json.data.data || []; });

  const customerInput = document.getElementById("customer_search");
  const customerId = document.getElementById("customer_id");
  const customerSuggestions = document.getElementById("customer_suggestions");

  customerInput.addEventListener("input", () => {
    const keyword = customerInput.value.toLowerCase();
    customerSuggestions.innerHTML = "";
    customerId.value = "";

    if (!keyword.trim()) return customerSuggestions.classList.add("d-none");

    const matches = allCustomers.filter(c => c.name.toLowerCase().includes(keyword));
    if (!matches.length) return customerSuggestions.classList.add("d-none");

    matches.forEach(c => {
      const item = document.createElement("button");
      item.type = "button";
      item.className = "list-group-item list-group-item-action";

      const group = c.group_name ? ` - ${c.group_name}` : "";
      const address = c.address ? ` - ${c.address}` : "";

      item.textContent = `${c.name}${group}${address}`;

      item.addEventListener("click", () => {
        customerInput.value = `${c.name}${group}${address}`;
        customerId.value = c.id;
        customerSuggestions.classList.add("d-none");
      });

      customerSuggestions.appendChild(item);
    });

    customerSuggestions.classList.remove("d-none");
  });

  document.addEventListener("click", e => {
    if (!customerInput.contains(e.target) && !customerSuggestions.contains(e.target)) {
      customerSuggestions.classList.add("d-none");
    }
  });

  // --------------------------
  // LOAD & SEARCH SẢN PHẨM
  // --------------------------
  let allProducts = [];
  fetch("/api/product/search")
    .then(res => res.json())
    .then(json => { allProducts = json.data || []; });

  const productInput = document.getElementById("product_search");
  const productSug = document.getElementById("product_suggestions");
  const selectedTable = document.getElementById("selected_products");

  let selectedProducts = {};

  productInput.addEventListener("input", () => {
    const keyword = productInput.value.toLowerCase();
    productSug.innerHTML = "";

    if (!keyword.trim()) return productSug.classList.add("d-none");

    const matches = allProducts.filter(p => p.name.toLowerCase().includes(keyword));
    if (!matches.length) return productSug.classList.add("d-none");

    matches.forEach(p => {
      const item = document.createElement("button");
      item.type = "button";
      item.className = "list-group-item list-group-item-action";
      item.textContent = `${p.name} - Giá ${Number(p.price).toLocaleString()} đ - Số lượng ${p.quantity} - Tồn ${p.days_in_stock} ngày`;

      item.addEventListener("click", () => {
        addProduct(p);
        productInput.value = "";
        productSug.classList.add("d-none");
      });

      productSug.appendChild(item);
    });

    productSug.classList.remove("d-none");
  });

  document.addEventListener("click", e => {
    if (!productInput.contains(e.target) && !productSug.contains(e.target)) {
      productSug.classList.add("d-none");
    }
  });

  // --------------------------
  // THÊM SẢN PHẨM VÀO BẢNG
  // --------------------------
  function addProduct(p) {
    if (selectedProducts[p.id]) return;

    selectedProducts[p.id] = {
      id: p.id,
      name: p.name,
      price: parseInt(p.price),
      quantity: 1,
      discount: 0,
      is_gift: false,
      import_product_id: p.import_product_id ?? null,
      max_quantity: parseInt(p.quantity) 
    };

    renderProducts();
  }

  // --------------------------
  // RENDER DANH SÁCH SẢN PHẨM
  // --------------------------
  function renderProducts() {
    selectedTable.innerHTML = "";

    Object.values(selectedProducts).forEach(prod => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${prod.name}</td>
        <td><input type="number" min="1" class="form-control form-control-sm text-end qty-input" data-id="${prod.id}" value="${prod.quantity}"></td>
        <td><span>${prod.price.toLocaleString()}</span></td>
        <td><input type="number" min="0" class="form-control form-control-sm text-end discount-input" data-id="${prod.id}" value="${prod.discount}"></td>
        <td class="text-center"><input type="checkbox" class="form-check-input gift-checkbox" data-id="${prod.id}" ${prod.is_gift ? "checked" : ""}></td>
        <td><span class="item-total" data-id="${prod.id}">0</span> đ</td>
        <td class="text-center"><button class="btn btn-sm btn-outline-secondary remove-btn" data-id="${prod.id}">Xóa</button></td>
      `;
      selectedTable.appendChild(row);
    });

    calculateTotal();
    bindEvents();
  }

  function bindEvents() {
    document.querySelectorAll(".qty-input").forEach(inp => {
      inp.addEventListener("input", e => {
        const id = e.target.dataset.id;
        let val = parseInt(e.target.value) || 1;

        const max = selectedProducts[id].max_quantity;

        if (val > max) {
          val = max;
          e.target.value = max;
          alert("Số lượng vượt quá tồn kho!");
        }

        selectedProducts[id].quantity = val;
        calculateTotal();
      });
    });

    document.querySelectorAll(".discount-input").forEach(inp => {
      inp.addEventListener("input", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].discount = parseInt(e.target.value) || 0;
        calculateTotal();
      });
    });

    document.querySelectorAll(".gift-checkbox").forEach(cb => {
      cb.addEventListener("change", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].is_gift = e.target.checked;
        calculateTotal();
      });
    });

    document.querySelectorAll(".remove-btn").forEach(btn => {
      btn.addEventListener("click", e => {
        const id = e.target.dataset.id;
        delete selectedProducts[id];
        renderProducts();
      });
    });
  }

  function calculateTotal() {
    let total = 0;

    Object.values(selectedProducts).forEach(p => {
      let itemTotal = 0;
      if (!p.is_gift) {
        const priceAfterDiscount = Math.max(p.price - p.discount, 0);
        itemTotal = priceAfterDiscount * p.quantity;
      }
      document.querySelector(`.item-total[data-id="${p.id}"]`).textContent = itemTotal.toLocaleString();
      total += itemTotal;
    });

    document.getElementById("total_amount").textContent = total.toLocaleString();
  }

  // --------------------------
  // SUBMIT FORM
  // --------------------------
  const form = document.getElementById("export-add-form");

  form.addEventListener("submit", async e => {
    e.preventDefault();

    for (const p of Object.values(selectedProducts)) {
      if (p.quantity > p.max_quantity) {
        alert(`Sản phẩm "${p.name}" chỉ còn ${p.max_quantity} trong kho`);
        return;
      }
    }

    const payload = {
      customer_id: customerId.value,
      description: document.getElementById("description").value,
      status: document.getElementById("status").value,
      payment_status: document.getElementById("payment_status").value,
      product: Object.values(selectedProducts)
    };

    if (!payload.customer_id) {
      document.getElementById("error-customer").textContent = "Vui lòng chọn khách hàng.";
      document.getElementById("error-customer").classList.remove("d-none");
      return;
    }

    const response = await fetch("/api/export/create", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const result = await response.json();
    if (result.success) {
      alert("Thêm đơn hàng thành công!");
      window.location.href = "/export";
    } else {
      alert(result.message || "Thêm đơn hàng thất bại!");
    }
  });
});
</script>