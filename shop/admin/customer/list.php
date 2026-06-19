<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-2 w-50">
      <input type="text" id="filter-name" class="form-control form-control-sm w-50" placeholder="Tìm theo tên khách hàng">

      <!-- FILTER NHÓM KHÁCH HÀNG -->
      <select id="filter-group" class="form-select form-select-sm w-50">
        <option value="">Tất cả nhóm</option>
      </select>
    </div>

    <a href="<?php echo url('/customer/create'); ?>" class="btn btn-sm btn-secondary">Thêm khách hàng</a>
  </div>

  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tên khách hàng</th>
          <th>Nhóm khách hàng</th>
          <th>Điện thoại</th>
          <th>Email</th>
          <th>Địa chỉ</th>
          <th>Ngày tạo</th>
          <th>Ngày cập nhật</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="customer-table-body">
        <!-- JS load dữ liệu -->
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination"></ul>
  </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  let allCustomers = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  // -------------------------------
  // Lấy danh sách khách hàng
  // -------------------------------
  async function loadCustomers() {
    try {
      const res = await fetch("/api/customer/list");
      const json = await res.json();
      allCustomers = json.success ? json.data : [];
      renderGroupFilter();
      currentPage = 1;
      renderTable(filteredCustomers());
    } catch (err) {
      console.error(err);
      allCustomers = [];
      renderTable([]);
    }
  }

  // -------------------------------
  // Render filter nhóm khách hàng
  // -------------------------------
  function renderGroupFilter() {
    const select = document.getElementById("filter-group");
    const groups = [...new Set(allCustomers.map(c => (c.group_name ?? '').trim()).filter(Boolean))];

    select.innerHTML = `<option value="">Tất cả nhóm</option>`;
    groups.forEach(group => {
      const option = document.createElement("option");
      option.value = group;
      option.textContent = group;
      select.appendChild(option);
    });
  }

  // -------------------------------
  // Lọc theo tên + nhóm
  // -------------------------------
  function filteredCustomers() {
    const keyword = document.getElementById("filter-name").value.toLowerCase();
    const group = document.getElementById("filter-group").value;

    return allCustomers.filter(c => {
      const matchName = c.name.toLowerCase().includes(keyword);
      const matchGroup = !group || c.group_name === group;
      return matchName && matchGroup;
    });
  }

  // -------------------------------
  // Render bảng
  // -------------------------------
  function renderTable(customers) {
    const tbody = document.getElementById("customer-table-body");
    tbody.innerHTML = "";

    if (!customers.length) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">Không có khách hàng</td></tr>`;
      renderPagination(0);
      return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = customers.slice(start, start + itemsPerPage);

    paginatedItems.forEach((item, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <th>${start + index + 1}</th>
        <td>${item.name || '—'}</td>
        <td>${item.group_name || '—'}</td>
        <td>${item.phone || '—'}</td>
        <td>${item.email || '—'}</td>
        <td>${item.address || '—'}</td>
        <td>${item.created_at || '—'}</td>
        <td>${item.updated_at || '—'}</td>
        <td>
          <a href="/customer/edit?id=${item.id}" class="btn btn-sm btn-outline-secondary me-1">Sửa</a>
          <button class="btn btn-sm btn-outline-secondary btn-delete" data-id="${item.id}">Xóa</button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    renderPagination(customers.length);
  }

  // -------------------------------
  // Phân trang
  // -------------------------------
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
        renderTable(filteredCustomers());
      });

      pagination.appendChild(li);
    }
  }


  // -------------------------------
  // Event filter
  // -------------------------------
  document.getElementById("filter-name").addEventListener("input", () => {
    currentPage = 1;
    renderTable(filteredCustomers());
  });

  document.getElementById("filter-group").addEventListener("change", () => {
    currentPage = 1;
    renderTable(filteredCustomers());
  });

  loadCustomers();
});
</script>