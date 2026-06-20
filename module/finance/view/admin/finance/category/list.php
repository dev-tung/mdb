<?php require_once PATH_FINANCE . 'service/category.php'; ?>

<?php $result = finance_category_service(); ?>

<div class="container-fluid py-4 mt-5">

  <!-- FILTER -->
  <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">

      <input type="text"
             name="keyword"
             class="form-control form-control-sm"
             placeholder="Tìm theo tên / type"
             value="<?= htmlspecialchars($result['filters']['keyword'] ?? '') ?>">

      <!-- TYPE -->
      <select name="type" class="form-select form-select-sm">
        <option value="">Tất cả loại</option>
        <option value="income"  <?= ($result['filters']['type'] ?? '') == 'income' ? 'selected' : '' ?>>Thu</option>
        <option value="expense" <?= ($result['filters']['type'] ?? '') == 'expense' ? 'selected' : '' ?>>Chi</option>
      </select>

      <!-- STATUS -->
      <select name="status" class="form-select form-select-sm">
        <option value="-1">Tất cả trạng thái</option>
        <option value="1" <?= ($result['filters']['status'] ?? -1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
        <option value="0" <?= ($result['filters']['status'] ?? -1) == 0 ? 'selected' : '' ?>>Tạm khóa</option>
      </select>

      <button type="submit" class="btn btn-sm btn-secondary">
        Lọc
      </button>

      <a href="<?= url('/admin/finance/category') ?>"
         class="btn btn-sm btn-outline-secondary">
        Xóa
      </a>

    </div>

    <div class="d-flex gap-2">

      <a href="<?= url('/admin/finance/category/create') ?>"
         class="btn btn-sm btn-secondary">
        Thêm danh mục
      </a>

    </div>

  </form>

  <!-- TABLE -->
  <div class="table-responsive">

    <table class="table table-sm table-striped table-borderless align-middle mb-0">

      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tên danh mục</th>
          <th>Loại</th>
          <th>Thứ tự</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($result['categories'])): ?>

          <tr>
            <td colspan="6" class="text-center text-muted py-3">
              Không tìm thấy danh mục nào.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($result['categories'] as $index => $c): ?>

            <tr data-id="<?= $c['id'] ?>">

              <td>
                <?= finance_category_index($result['page'], $index) ?>
              </td>

              <td>
                <?= htmlspecialchars($c['name'] ?? '') ?>
              </td>

              <td>
                <?= htmlspecialchars($c['type'] ?? '') ?>
              </td>

              <td>
                <?= (int)($c['sort_order'] ?? 0) ?>
              </td>

              <td>
                <?php if (($c['status'] ?? 0) == 1): ?>
                  <span class="badge bg-success">Hoạt động</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Tạm khóa</span>
                <?php endif; ?>
              </td>

              <td class="text-nowrap">

                <a href="<?= url('/admin/finance/category/edit?id=' . $c['id']) ?>"
                   class="btn btn-sm btn-outline-secondary">
                  Sửa
                </a>

                <!-- DELETE FETCH -->
                <button type="button"
                        class="btn btn-sm btn-outline-secondary btn-delete-category"
                        data-id="<?= $c['id'] ?>">
                  Xóa
                </button>

              </td>

            </tr>

          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

  <!-- PAGINATION -->
  <?php
    echo pager([
        'page'  => $result['page'],
        'total' => $result['totalPages'],
        'query' => $_GET
    ]);
  ?>

</div>

<!-- =========================
     DELETE AJAX SCRIPT
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  document.querySelectorAll(".btn-delete-category").forEach(btn => {

    btn.addEventListener("click", async function () {

      const id = this.dataset.id;

      if (!confirm("Xóa danh mục này?")) return;

      this.disabled = true;

      try {

        const res = await fetch("<?= url('/api/finance/category/delete') ?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ id })
        });

        const json = await res.json();

        if (json.success) {

          this.closest("tr").remove();

        } else {

          alert(json.message || "Xóa thất bại!");
          this.disabled = false;

        }

      } catch (err) {

        console.error(err);
        alert("Lỗi hệ thống!");
        this.disabled = false;

      }

    });

  });

});
</script>