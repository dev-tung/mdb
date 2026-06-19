<?php require_once PATH_ROOT . 'navbar.php'; ?>
<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between mb-3">
    <input type="text" id="filter-name" class="form-control form-control-sm w-25" placeholder="Tìm theo tên nhà cung cấp">
    <a href="<?php echo url('/supplier/create'); ?>" class="btn btn-sm btn-secondary">Thêm nhà cung cấp</a>
  </div>

  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tên nhà cung cấp</th>
          <th>Điện thoại</th>
          <th>Email</th>
          <th>Địa chỉ</th>
          <th>Ngày tạo</th>
          <th>Ngày cập nhật</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="supplier-table-body">
        <!-- JS load dữ liệu -->
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <nav aria-label="Phân trang" class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">
      <!-- JS render pagination -->
    </ul>
  </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  let allSuppliers = [];
  let currentPage = 1;
  const itemsPerPage = 100;

  // -------------------------------
  // Lấy danh sách nhà cung cấp từ API
  // -------------------------------
  async function loadSuppliers() {
    try {
      const res = await fetch("/api/supplier/list");
      const json = await res.json();
      allSuppliers = json.success ? json.data : [];
      currentPage = 1;
      renderTable(filteredSuppliers());
    } catch (err) {
      console.error("Lỗi API:", err);
      allSuppliers = [];
      renderTable([]);
    }
  }

  // -------------------------------
  // Lọc nhà cung cấp theo keyword
  // -------------------------------
  function filteredSuppliers() {
    const keyword = document.getElementById("filter-name").value.toLowerCase();
    return allSuppliers.filter(c => c.name.toLowerCase().includes(keyword));
  }

  // -------------------------------
  // Render bảng nhà cung cấp
  // -------------------------------
  function renderTable(suppliers) {
    const tbody = document.getElementById("supplier-table-body");
    tbody.innerHTML = "";

    if (!suppliers.length) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">Không có nhà cung cấp</td></tr>`;
      renderPagination(0);
      return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = suppliers.slice(start, start + itemsPerPage);

    paginatedItems.forEach((item, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <th scope="row">${start + index + 1}</th>
          <td>${((item.name ?? '')).toString().trim() || '—'}</td>
          <td>${((item.phone ?? '')).toString().trim() || '—'}</td>
          <td>${((item.email ?? '')).toString().trim() || '—'}</td>
          <td>${((item.address ?? '')).toString().trim() || '—'}</td>
          <td>${((item.created_at ?? '')).toString().trim() || '—'}</td>
          <td>${((item.updated_at ?? '')).toString().trim() || '—'}</td>
        <td>
          <a href="/supplier/edit?id=${item.id}" class="btn btn-sm btn-outline-secondary me-1">Sửa</a>
          <button class="btn btn-sm btn-outline-secondary btn-delete" data-id="${item.id}">Xóa</button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    // Gắn sự kiện cho nút Delete
    tbody.querySelectorAll(".btn-delete").forEach(btn => {
      btn.addEventListener("click", async function() {
        const id = this.dataset.id;
        if (!confirm("Bạn có chắc chắn muốn xóa nhà cung cấp này?")) return;

        try {
          const res = await fetch(`/api/supplier/delete?id=${id}`, { method: "DELETE" });
          const result = await res.json();
          alert(result.message);
          if (result.success) {
            allSuppliers = allSuppliers.filter(c => c.id != id);
            const totalPages = Math.ceil(filteredSuppliers().length / itemsPerPage);
            if (currentPage > totalPages) currentPage = totalPages || 1;
            renderTable(filteredSuppliers());
          }
        } catch (err) {
          console.error(err);
          alert("Có lỗi xảy ra, vui lòng thử lại.");
        }
      });
    });

    renderPagination(suppliers.length);
  }

  // -------------------------------
  // Render phân trang
  // -------------------------------
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
        renderTable(filteredSuppliers());
      });
      pagination.appendChild(li);
    }
  }

  // -------------------------------
  // Filter input
  // -------------------------------
  document.getElementById("filter-name").addEventListener("input", function() {
    currentPage = 1;
    renderTable(filteredSuppliers());
  });

  // -------------------------------
  // Khởi tạo
  // -------------------------------
  loadSuppliers();
});
</script>