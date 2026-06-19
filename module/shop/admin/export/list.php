<div class="container-fluid py-4 mt-5">

  <!-- Bộ lọc -->
  <div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-2 w-50">

      <!-- Lọc theo tên -->
      <input 
        type="text" 
        id="filter-name" 
        class="form-control form-control-sm w-50" 
        placeholder="Tìm theo tên khách hàng"
      >

      <!-- Lọc theo ngày tạo -->
      <input 
        type="date" 
        id="filter-date-from" 
        class="form-control form-control-sm w-25"
      >

      <input 
        type="date" 
        id="filter-date-to" 
        class="form-control form-control-sm w-25"
      >

      <!-- Lọc theo trạng thái -->
      <select id="filter-status" class="form-select form-select-sm w-25">
        <option value="">Trạng thái</option>
        <?php foreach (option('product_status') as $key => $label): ?>
          <option value="<?= $key ?>"><?= $label ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Lọc theo thanh toán -->
      <select id="filter-payment" class="form-select form-select-sm w-25">
        <option value="">Thanh toán</option>
        <?php foreach (option('payment_status') as $key => $label): ?>
          <option value="<?= $key ?>"><?= $label ?></option>
        <?php endforeach; ?>
      </select>

    </div>

    <a href="<?php echo url('/export/create'); ?>" class="btn btn-sm btn-secondary">
      Tạo đơn bán hàng
    </a>
  </div>

  <!-- Tổng hợp -->
  <div class="my-3">
    <strong>Tổng tiền</strong> <span id="total-amount">0</span>
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Khách hàng</th>
          <th>Nhóm</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="customer-table-body"></tbody>
    </table>
  </div>

  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination"></ul>
  </nav>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

  let allExports = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  const STATUS_OPTIONS  = <?php echo json_encode(option('product_status')); ?>;
  const PAYMENT_OPTIONS = <?php echo json_encode(option('payment_status')); ?>;

  // Load dữ liệu từ API
  async function loadExports() {
    try {
      const res = await fetch("/api/export/list");
      const json = await res.json();
      allExports = json.success ? json.data : [];
      currentPage = 1;
      renderTable(filteredExports());
    } catch (err) {
      console.error("Lỗi API:", err);
      renderTable([]);
    }
  }

  // Hàm lọc (Tên + Ngày + Status + Payment)
  function filteredExports() {
    const keyword = document.getElementById("filter-name").value.toLowerCase();
    const statusFilter = document.getElementById("filter-status").value;
    const paymentFilter = document.getElementById("filter-payment").value;
    const dateFrom = document.getElementById("filter-date-from").value;
    const dateTo = document.getElementById("filter-date-to").value;

    return allExports.filter(e => {
      const matchName = e.customer_name.toLowerCase().includes(keyword);
      const matchStatus = statusFilter ? e.status === statusFilter : true;
      const matchPayment = paymentFilter ? e.payment_status === paymentFilter : true;

      let matchDate = true;
      if (dateFrom) {
        matchDate = new Date(e.created_at) >= new Date(dateFrom);
      }
      if (matchDate && dateTo) {
        const toDate = new Date(dateTo);
        toDate.setHours(23, 59, 59, 999);
        matchDate = new Date(e.created_at) <= toDate;
      }

      return matchName && matchStatus && matchPayment && matchDate;
    });
  }

  // Format tiền VND
  function formatVND(amount) {
    return Number(amount).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
  }

  function updateTotal(exportsList) {
    const total = exportsList.reduce((sum, item) => sum + Number(item.total_amount), 0);
    document.getElementById("total-amount").textContent = formatVND(total);
  }

  // Render bảng
  function renderTable(exportsList) {
    const tbody = document.getElementById("customer-table-body");
    tbody.innerHTML = "";

    updateTotal(exportsList);

    if (!exportsList.length) {
      tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted">Không có dữ liệu</td></tr>`;
      renderPagination(0);
      return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = exportsList.slice(start, start + itemsPerPage);

    paginatedItems.forEach((item, index) => {

      function getStatusClass(value, type = "status") {
        if (type === 'status' && value !== 'completed') return 'text-danger';
        if (type === 'payment' && value !== 'paid') return 'text-danger';
        return '';
      }

      const statusDropdown = `
        <select class="form-select form-select-sm update-status ${getStatusClass(item.status, 'status')}" data-id="${item.id}">
          ${Object.entries(STATUS_OPTIONS).map(([key, label]) =>
            `<option value="${key}" ${key === item.status ? 'selected' : ''}>${label}</option>`
          ).join('')}
        </select>`;

      const paymentDropdown = `
        <select class="form-select form-select-sm update-payment ${getStatusClass(item.payment_status, 'payment')}" data-id="${item.id}">
          ${Object.entries(PAYMENT_OPTIONS).map(([key, label]) =>
            `<option value="${key}" ${key === item.payment_status ? 'selected' : ''}>${label}</option>`
          ).join('')}
        </select>`;

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <th scope="row">${start + index + 1}</th>
        <td>${item.customer_name}</td>
        <td>${((item.customer_group ?? '')).toString().trim() || '—'}</td>
        <td>${formatVND(item.total_amount)}</td>
        <td>${statusDropdown}</td>
        <td>${paymentDropdown}</td>
        <td>${((item.created_at ?? '')).toString().trim() || '—'}</td>
        <td>
          <a href="/admin/export/edit?id=${item.id}" class="btn btn-sm btn-outline-secondary">Sửa</a>
          <button class="btn btn-sm btn-outline-secondary btn-delete" data-id="${item.id}">Xóa</button>
        </td>
      `;

      tbody.appendChild(tr);
    });

    // Update status
    document.querySelectorAll(".update-status").forEach(select => {
      select.addEventListener("change", async function () {
        const id = this.dataset.id;
        const value = this.value;

        try {
          const res = await fetch(`/api/export/status?id=${id}`, {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: value })
          });
          const result = await res.json();
          if (result.success) loadExports();
          else alert(result.message || "Cập nhật thất bại");
        } catch (err) {
          alert("Có lỗi xảy ra");
        }
      });
    });

    // Update payment
    document.querySelectorAll(".update-payment").forEach(select => {
      select.addEventListener("change", async function () {
        const id = this.dataset.id;
        const value = this.value;

        try {
          const res = await fetch(`/api/export/payment?id=${id}`, {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ payment_status: value })
          });
          const result = await res.json();
          if (result.success) loadExports();
          else alert(result.message || "Cập nhật thất bại");
        } catch (err) {
          alert("Có lỗi xảy ra");
        }
      });
    });

    // Delete
    tbody.querySelectorAll(".btn-delete").forEach(btn => {
      btn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!confirm("Bạn có chắc chắn muốn xóa đơn hàng này?")) return;

        try {
          const res = await fetch(`/api/export/delete?id=${id}`, { method: "DELETE" });
          const result = await res.json();
          alert(result.message);

          if (result.success) {
            allExports = allExports.filter(e => e.id != id);
            const totalPages = Math.ceil(filteredExports().length / itemsPerPage);
            if (currentPage > totalPages) currentPage = totalPages || 1;
            renderTable(filteredExports());
          }
        } catch (err) {
          alert("Có lỗi xảy ra, vui lòng thử lại.");
        }
      });
    });

    renderPagination(exportsList.length);
  }

  // Render phân trang
  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === currentPage ? "active" : ""}`;
      li.innerHTML = `<a class="page-link text-secondary ${i === currentPage ? "bg-light border-secondary" : ""}" href="#">${i}</a>`;
      li.addEventListener("click", function(e) {
        e.preventDefault();
        currentPage = i;
        renderTable(filteredExports());
      });
      pagination.appendChild(li);
    }
  }

  // Sự kiện lọc
  document.getElementById("filter-name").addEventListener("input", function () {
    currentPage = 1;
    renderTable(filteredExports());
  });

  document.getElementById("filter-status").addEventListener("change", function () {
    currentPage = 1;
    renderTable(filteredExports());
  });

  document.getElementById("filter-payment").addEventListener("change", function () {
    currentPage = 1;
    renderTable(filteredExports());
  });

  document.getElementById("filter-date-from").addEventListener("change", function () {
    currentPage = 1;
    renderTable(filteredExports());
  });

  document.getElementById("filter-date-to").addEventListener("change", function () {
    currentPage = 1;
    renderTable(filteredExports());
  });

  loadExports();
});
</script>
