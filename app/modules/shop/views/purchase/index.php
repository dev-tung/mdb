<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input type="date" id="filter-date-from" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input type="date" id="filter-date-to" class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <select id="filter-supplier" class="form-select form-select-sm">
          <option value="">Nhà cung cấp</option>
        </select>
      </div>

    </div>

    <a href="/admin/purchases/create" class="btn btn-sm btn-outline-secondary">
      Thêm phiếu nhập
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng phiếu nhập:</strong>
    <span id="total-amount">0</span>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Nhà cung cấp</th>
          <th>Kho</th>
          <th>Tổng tiền</th>
          <th>Số sản phẩm</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody id="purchase-table-body">
        <tr>
          <td colspan="8" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>
        </tr>
      </tbody>

    </table>
  </div>

  <nav class="mt-3">
    <ul class="pagination pagination-sm" id="pagination">

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(1)">Đầu</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(prevPage)">Trước</a>
      </li>

      <li class="page-item d-flex" id="pagination-pages"></li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(nextPage)">Sau</a>
      </li>

      <li class="page-item">
        <a class="page-link text-secondary" href="javascript:void(0)" onclick="goToPage(lastPage)">Cuối</a>
      </li>

    </ul>
  </nav>
</div>
<script>
let currentPage = 1;
let lastPage = 1;
let prevPage = 1;
let nextPage = 1;

/* =========================
   LOAD SUPPLIERS
========================= */
async function loadSuppliers() {
    const res = await fetch('/api/suppliers');
    const json = await res.json();

    const select = document.getElementById('filter-supplier');
    select.innerHTML = `<option value="">Nhà cung cấp</option>`;

    json.data.forEach(s => {
        select.innerHTML += `
            <option value="${s.id}">${s.name}</option>
        `;
    });
}

/* =========================
   LOAD PURCHASES
========================= */
async function loadPurchases(page = 1) {

    currentPage = page;

    const supplier = document.getElementById('filter-supplier').value;
    const dateFrom = document.getElementById('filter-date-from').value;
    const dateTo = document.getElementById('filter-date-to').value;

    const query = new URLSearchParams({
        page,
        supplier_id: supplier,
        date_from: dateFrom,
        date_to: dateTo
    });

    const res = await fetch(`/api/purchases?${query.toString()}`);
    const json = await res.json();

    const tbody = document.getElementById('purchase-table-body');
    tbody.innerHTML = '';

    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted">
                    Không có phiếu nhập nào
                </td>
            </tr>`;
        return;
    }

    json.data.forEach((p, index) => {
        tbody.innerHTML += `
            <tr>
                <td>${(json.meta.page - 1) * json.meta.perPage + index + 1}</td>

                <!-- NHÀ CUNG CẤP -->
                <td>${p.supplier_name ?? '---'}</td>

                <!-- KHO -->
                <td>${p.warehouse_name ?? '---'}</td>

                <td>${Number(p.total_cost).toLocaleString()} ₫</td>

                <td>${p.total_items ?? 0}</td>

                <td>
                    <select class="form-select form-select-sm"
                            onchange="updateStatus(${p.id}, this.value)">
                        <option value="pending" ${p.status == 'pending' ? 'selected' : ''}>Chờ xử lý</option>
                        <option value="completed" ${p.status == 'completed' ? 'selected' : ''}>Hoàn thành</option>
                        <option value="cancelled" ${p.status == 'cancelled' ? 'selected' : ''}>Huỷ</option>
                    </select>
                </td>

                <td>${p.created_at ?? ''}</td>

                <td>
                    <a href="/admin/purchases/edit/${p.id}" class="btn btn-sm btn-outline-secondary">
                        Sửa
                    </a>

                    <button class="btn btn-sm btn-outline-secondary"
                            onclick="deletePurchase(${p.id})">
                        Xóa
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById('total-amount').innerText = json.meta.total;

    lastPage = json.meta.totalPages;
    prevPage = Math.max(1, json.meta.page - 1);
    nextPage = Math.min(lastPage, json.meta.page + 1);

    renderPages(json.meta.page, json.meta.totalPages);
}

/* =========================
   UPDATE STATUS
========================= */
async function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);

    const res = await fetch('/api/purchases/update-status', {
        method: 'POST',
        body: formData
    });

    const json = await res.json();

    if (!json.success) {
        alert(json.message || 'Cập nhật thất bại');
        loadPurchases(currentPage);
    }
}

/* =========================
   DELETE PURCHASE
========================= */
async function deletePurchase(id) {
    if (!confirm('Bạn có chắc muốn xóa phiếu nhập này?')) return;

    const formData = new FormData();
    formData.append('id', id);

    const res = await fetch('/api/purchases/delete', {
        method: 'POST',
        body: formData
    });

    const json = await res.json();

    if (json.success) {
        alert('Xóa thành công');
        loadPurchases(currentPage);
    } else {
        alert(json.message || 'Xóa thất bại');
    }
}

/* =========================
   PAGINATION
========================= */
function goToPage(page) {
    loadPurchases(page);
}

function renderPages(page, totalPages) {

    const container = document.getElementById('pagination-pages');
    container.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {

        if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {

            container.innerHTML += `
                <li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link text-secondary ${i === page ? 'bg-light border-secondary' : ''}"
                       href="javascript:void(0)"
                       onclick="goToPage(${i})">
                        ${i}
                    </a>
                </li>`;
        }
    }
}

/* =========================
   FILTER EVENTS
========================= */
document.querySelectorAll(
    '#filter-supplier, #filter-date-from, #filter-date-to'
).forEach(el => {
    el.addEventListener('input', () => loadPurchases(1));
    el.addEventListener('change', () => loadPurchases(1));
});

/* =========================
   INIT
========================= */
(async function init() {
    await loadSuppliers();
    await loadPurchases(1);
})();
</script>