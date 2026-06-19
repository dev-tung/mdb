<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between mb-3 gap-2">
    <div class="d-flex gap-2 w-50">
      <input type="text" id="filter-name" class="form-control form-control-sm" placeholder="Tìm theo tên nhà cung cấp">

      <div class="d-flex gap-2">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>

      <!-- FILTER TRẠNG THÁI -->
      <select id="filter-status" class="form-select form-select-sm w-auto">
        <option value="">Trạng thái</option>
        <?php foreach(option('product_status') as $key => $label): ?>
          <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select>

      <!-- FILTER THANH TOÁN -->
      <select id="filter-payment" class="form-select form-select-sm w-auto">
        <option value="">Thanh toán</option>
        <?php foreach(option('payment_status') as $key => $label): ?>
          <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <a href="<?php echo url('/import/create'); ?>" class="btn btn-sm btn-secondary">Tạo đơn nhập hàng</a>
  </div>

  <!-- TỔNG TIỀN -->
  <div class="mb-2">
    <strong>Tổng tiền</strong>
    <span id="total-amount">0 ₫</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nhà cung cấp</th>
          <th>Địa chỉ</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="supplier-table-body"></tbody>
    </table>
  </div>

  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination"></ul>
  </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  let allImports = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  const STATUS_OPTIONS  = <?php echo json_encode(option('product_status')); ?>;
  const PAYMENT_OPTIONS = <?php echo json_encode(option('payment_status')); ?>;

  async function loadImports() {
    try {
      const res = await fetch("/api/import/list");
      const json = await res.json();
      allImports = json.success ? json.data : [];
      currentPage = 1;
      renderTable(filteredImports());
    } catch (err) {
      console.error("Lỗi API:", err);
      allImports = [];
      renderTable([]);
    }
  }

  function filteredImports() {
    const keyword   = document.getElementById("filter-name").value.toLowerCase();
    const dateFrom  = document.getElementById("filter-date-from").value;
    const dateTo    = document.getElementById("filter-date-to").value;
    const statusFilter  = document.getElementById("filter-status").value;
    const paymentFilter = document.getElementById("filter-payment").value;

    return allImports.filter(item => {
      const supplierMatch = (item.supplier_name || '').toLowerCase().includes(keyword);

      let dateMatch = true;
      if (dateFrom || dateTo) {
        const createdDate = item.created_at ? item.created_at.substring(0, 10) : '';
        if (dateFrom && createdDate < dateFrom) dateMatch = false;
        if (dateTo && createdDate > dateTo) dateMatch = false;
      }

      const statusMatch  = !statusFilter  || item.status === statusFilter;
      const paymentMatch = !paymentFilter || item.payment_status === paymentFilter;

      return supplierMatch && dateMatch && statusMatch && paymentMatch;
    });
  }

  function formatVND(amount) {
    return Number(amount).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
  }

  function renderTotalAmount(list) {
    const total = list.reduce((sum, item) => {
      return sum + Number(item.total_amount || 0);
    }, 0);

    document.getElementById("total-amount").innerText =
      total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
  }

  function renderTable(importsList) {
    const tbody = document.getElementById("supplier-table-body");
    tbody.innerHTML = "";

    if (!importsList.length) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Không có dữ liệu</td></tr>`;
      renderPagination(0);
      renderTotalAmount([]);
      return;
    }

    renderTotalAmount(importsList);

    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = importsList.slice(start, start + itemsPerPage);

    paginatedItems.forEach((item, index) => {
      const tr = document.createElement("tr");

      function getStatusClass(value, type = 'status') {
        if (type === 'status' && value !== 'completed') return 'text-danger';
        if (type === 'payment' && value !== 'paid') return 'text-danger';
        return '';
      }

      const statusDropdown = `
        <select class="form-select form-select-sm update-status ${getStatusClass(item.status, 'status')}" data-id="${item.id}">
          ${Object.entries(STATUS_OPTIONS).map(([key, label]) =>
            `<option value="${key}" ${key === item.status ? 'selected' : ''}>${label}</option>`
          ).join('')}
        </select>
      `;

      const paymentDropdown = `
        <select class="form-select form-select-sm update-payment ${getStatusClass(item.payment_status, 'payment')}" data-id="${item.id}">
          ${Object.entries(PAYMENT_OPTIONS).map(([key, label]) =>
            `<option value="${key}" ${key === item.payment_status ? 'selected' : ''}>${label}</option>`
          ).join('')}
        </select>
      `;

      tr.innerHTML = `
        <th scope="row">${start + index + 1}</th>
        <td>${item.supplier_name}</td>
        <td>${(item.supplier_address ?? '').toString().trim() || '—'}</td>
        <td>${formatVND(item.total_amount)}</td>
        <td>${statusDropdown}</td>
        <td>${paymentDropdown}</td>
        <td>${item.created_at || '—'}</td>
        <td>
          <a href="/import/edit?id=${item.id}" class="btn btn-sm btn-outline-secondary">Sửa</a>
          <button class="btn btn-sm btn-outline-secondary btn-delete" data-id="${item.id}">Xóa</button>
        </td>
      `;

      tbody.appendChild(tr);
    });

    document.querySelectorAll(".update-status").forEach(select => {
      select.addEventListener("change", async function() {
        const res = await fetch(`/api/import/update-status?id=${this.dataset.id}`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ status: this.value })
        });
        const result = await res.json();
        if (!result.success) alert(result.message);
        else loadImports();
      });
    });

    document.querySelectorAll(".update-payment").forEach(select => {
      select.addEventListener("change", async function() {
        const res = await fetch(`/api/import/update-payment?id=${this.dataset.id}`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ payment_status: this.value })
        });
        const result = await res.json();
        if (!result.success) alert(result.message);
        else loadImports();
      });
    });

    tbody.querySelectorAll(".btn-delete").forEach(btn => {
      btn.addEventListener("click", async function() {
        if (!confirm("Bạn có chắc chắn muốn xóa đơn hàng này?")) return;
        const res = await fetch(`/api/import/delete?id=${this.dataset.id}`, { method: "DELETE" });
        const result = await res.json();
        alert(result.message);
        if (result.success) loadImports();
      });
    });

    renderPagination(importsList.length);
  }

  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === currentPage ? "active" : ""}`;
      li.innerHTML = `
        <a class="page-link text-secondary ${i === currentPage ? "bg-light border-secondary" : ""}" href="#">
          ${i}
        </a>
      `;
      li.addEventListener("click", function(e) {
        e.preventDefault();
        currentPage = i;
        renderTable(filteredImports());
      });
      pagination.appendChild(li);
    }
  }

  document.getElementById("filter-name").addEventListener("input", () => {
    currentPage = 1;
    renderTable(filteredImports());
  });

  ["filter-date-from","filter-date-to","filter-status","filter-payment"]
    .forEach(id => document.getElementById(id).addEventListener("change", () => {
      currentPage = 1;
      renderTable(filteredImports());
    }));

  loadImports();
});
</script>
