
<div class="container-fluid py-4 mt-5">
  <div class="card shadow-sm w-100">
    <div class="card-body">
      <h3 class="card-title mb-4">Thêm đơn nhập hàng</h3>

      <form id="import-add-form" novalidate>
        <div class="row g-3">

          <!-- Search nhà cung cấp -->
          <div class="col-md-6 position-relative">
            <label class="form-label">Nhà cung cấp</label>
            <input type="text" id="supplier_search" class="form-control" placeholder="Tìm nhà cung cấp..." autocomplete="off">
            <input type="hidden" id="supplier_id">
            <div id="supplier_suggestions"
              class="list-group position-absolute w-100 d-none"
              style="max-height:220px; overflow-y:auto; z-index:1050;">
            </div>
            <small class="text-danger d-none" id="error-supplier"></small>
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
              <?php foreach (option('product_status') as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Status thanh toán -->
          <div class="col-md-6">
            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
            <select id="payment_status" class="form-select">
              <?php foreach (option('payment_status') as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Search sản phẩm -->
          <div class="col-12 position-relative mt-4">
            <label class="form-label">Sản phẩm</label>
            <input type="text" id="product_search" class="form-control" placeholder="Tìm sản phẩm...">
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
            <button type="submit" class="btn btn-secondary mt-3">Thêm đơn nhập hàng</button>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // --------------------------
  // LOAD NHÀ CUNG CẤP
  // --------------------------
  let allSuppliers = [];
  fetch("/api/supplier/list")
    .then(res => res.json())
    .then(json => { allSuppliers = json.data.data || []; });

  const supplierInput = document.getElementById("supplier_search");
  const supplierId = document.getElementById("supplier_id");
  const supplierSuggestions = document.getElementById("supplier_suggestions");

  supplierInput.addEventListener("input", () => {
    const keyword = supplierInput.value.toLowerCase();
    supplierSuggestions.innerHTML = "";
    supplierId.value = "";

    if (!keyword.trim()) return supplierSuggestions.classList.add("d-none");

    const matches = allSuppliers.filter(c => c.name.toLowerCase().includes(keyword));
    if (!matches.length) return supplierSuggestions.classList.add("d-none");

    matches.forEach(c => {
      const item = document.createElement("button");
      item.type = "button";
      item.className = "list-group-item list-group-item-action";

      item.textContent = `${c.name}`;

      item.addEventListener("click", () => {
        supplierInput.value = `${c.name}`;
        supplierId.value = c.id;
        supplierSuggestions.classList.add("d-none");
      });

      supplierSuggestions.appendChild(item);
    });

    supplierSuggestions.classList.remove("d-none");
  });

  document.addEventListener("click", e => {
    if (!supplierInput.contains(e.target) && !supplierSuggestions.contains(e.target)) {
      supplierSuggestions.classList.add("d-none");
    }
  });

  // --------------------------
  // LOAD & SEARCH SẢN PHẨM
  // --------------------------
  let allProducts = [];
  fetch("/api/product/list")
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
      item.textContent = p.name;

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
      price: 0,
      quantity: 1,
      discount: 0,
      is_gift: false
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
        <td><input type="number" min="0" class="form-control form-control-sm text-end price-input" data-id="${prod.id}" value="${prod.price}"></td>
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
    // Số lượng
    document.querySelectorAll(".qty-input").forEach(inp => {
      inp.addEventListener("input", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].quantity = parseInt(e.target.value) || 1;
        calculateTotal();
      });
    });

    // Giá nhập
    document.querySelectorAll(".price-input").forEach(inp => {
      inp.addEventListener("input", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].price = parseFloat(e.target.value) || 0;
        calculateTotal();
      });
    });

    // Giảm giá
    document.querySelectorAll(".discount-input").forEach(inp => {
      inp.addEventListener("input", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].discount = parseFloat(e.target.value) || 0;
        calculateTotal();
      });
    });

    // Quà tặng
    document.querySelectorAll(".gift-checkbox").forEach(cb => {
      cb.addEventListener("change", e => {
        const id = e.target.dataset.id;
        selectedProducts[id].is_gift = e.target.checked;
        calculateTotal();
      });
    });

    // Xóa sản phẩm
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
  const form = document.getElementById("import-add-form");

  form.addEventListener("submit", async e => {
    e.preventDefault();

    const payload = {
      supplier_id: supplierId.value,
      description: document.getElementById("description").value,
      status: document.getElementById("status").value,
      payment_status: document.getElementById("payment_status").value,
      product: Object.values(selectedProducts)
    };

    if (!payload.supplier_id) {
      document.getElementById("error-supplier").textContent = "Vui lòng chọn nhà cung cấp.";
      document.getElementById("error-supplier").classList.remove("d-none");
      return;
    }

    const response = await fetch("/api/import/create", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const result = await response.json();
    if (result.success) {
      alert("Thêm đơn nhập hàng thành công!");
      window.location.href = "/import";
    } else {
      alert(result.message || "Thêm đơn nhập hàng thất bại!");
    }
  });
});
</script>