<?php require_once PATH_FINANCE . 'service/category.php'; ?>

<div class="container-fluid py-4 mt-5">

  <div class="card shadow-sm w-100">
    <div class="card-body">

      <h3 class="card-title mb-4">Chỉnh sửa danh mục</h3>

      <form id="finance-category-edit-form">

        <input type="hidden" id="id">

        <div class="row g-3">

          <!-- NAME -->
          <div class="col-md-6">
            <label class="form-label">Tên danh mục</label>
            <input type="text" id="name" class="form-control">
          </div>

          <!-- TYPE -->
          <div class="col-md-6">
            <label class="form-label">Loại</label>
            <select id="type" class="form-select">
              <option value="income">Thu</option>
              <option value="expense">Chi</option>
              <option value="transfer">Chuyển khoản</option>
            </select>
          </div>

          <!-- SORT -->
          <div class="col-md-6">
            <label class="form-label">Thứ tự</label>
            <input type="number" id="sort_order" class="form-control">
          </div>

          <!-- STATUS -->
          <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select id="status" class="form-select">
              <option value="1">Hoạt động</option>
              <option value="0">Tạm khóa</option>
            </select>
          </div>

          <!-- BUTTON -->
          <div class="col-12 text-end mt-3">

            <a href="<?= url('/admin/finance/category') ?>"
               class="btn btn-outline-secondary">
              Hủy
            </a>

            <button type="submit" id="btn" class="btn btn-secondary">
              Cập nhật
            </button>

          </div>

        </div>

      </form>

    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", async function () {

  const id = new URLSearchParams(location.search).get("id");

  // LOAD DATA
  const res = await fetch("<?= url('/api/finance/category/show') ?>", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const json = await res.json();
  const data = json.data;

  document.getElementById("id").value = data.id;
  document.getElementById("name").value = data.name;
  document.getElementById("type").value = data.type;
  document.getElementById("sort_order").value = data.sort_order;
  document.getElementById("status").value = data.status;

  // UPDATE
  document.getElementById("finance-category-edit-form")
    .addEventListener("submit", async function (e) {

      e.preventDefault();

      const payload = {
        id: document.getElementById("id").value,
        name: document.getElementById("name").value,
        type: document.getElementById("type").value,
        sort_order: document.getElementById("sort_order").value,
        status: document.getElementById("status").value
      };

      const res = await fetch("<?= url('/api/finance/category/update') ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const json = await res.json();

      if (json.success) {
        alert("Cập nhật thành công!");
        location.href = "<?= url('/admin/finance/category') ?>";
      } else {
        alert(json.message || "Lỗi!");
      }

    });

});
</script>