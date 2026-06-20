<?php require_once PATH_FINANCE . 'service/transaction.php'; ?>

<?php $result = finance_transaction_service(); ?>

<div class="container-fluid py-4 mt-5">

  <!-- FILTER -->
  <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">

      <input type="text"
             name="keyword"
             class="form-control form-control-sm"
             placeholder="Tìm theo ghi chú / module"
             value="<?= htmlspecialchars($result['filters']['keyword'] ?? '') ?>">

      <!-- ACCOUNT (API LOAD) -->
      <select name="account_id"
              class="form-select form-select-sm"
              id="accountSelect">
        <option value="">Tất cả tài khoản</option>
      </select>

      <!-- CATEGORY (API LOAD) -->
      <select name="category_id"
              class="form-select form-select-sm"
              id="categorySelect">
        <option value="">Tất cả danh mục</option>
      </select>

      <!-- MODULE -->
      <select name="module" class="form-select form-select-sm">
        <option value="">Tất cả module</option>
        <option value="income"  <?= ($result['filters']['module'] ?? '') == 'income' ? 'selected' : '' ?>>Income</option>
        <option value="expense" <?= ($result['filters']['module'] ?? '') == 'expense' ? 'selected' : '' ?>>Expense</option>
      </select>

      <button type="submit" class="btn btn-sm btn-secondary">
        Lọc
      </button>

      <a href="<?= url('/admin/finance/transaction') ?>"
         class="btn btn-sm btn-outline-secondary">
        Xóa
      </a>

    </div>

    <div class="d-flex gap-2">

      <a href="<?= url('/admin/finance/transaction/create') ?>"
         class="btn btn-sm btn-secondary">
        Thêm giao dịch
      </a>

    </div>

  </form>

  <!-- TABLE -->
  <div class="table-responsive">

    <table class="table table-sm table-striped table-borderless align-middle mb-0">

      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tài khoản</th>
          <th>Danh mục</th>
          <th>Module</th>
          <th>Số tiền</th>
          <th>Ngày</th>
          <th>Ghi chú</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($result['transactions'])): ?>

          <tr>
            <td colspan="8" class="text-center text-muted py-3">
              Không tìm thấy giao dịch nào.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($result['transactions'] as $index => $t): ?>

            <tr data-id="<?= $t['id'] ?>">

              <td>
                <?= finance_transaction_index($result['page'], $index) ?>
              </td>

              <td><?= htmlspecialchars($t['account_name'] ?? '---') ?></td>
              <td><?= htmlspecialchars($t['category_name'] ?? '---') ?></td>
              <td><?= htmlspecialchars($t['module'] ?? '') ?></td>
              <td><?= number_format($t['amount'] ?? 0) ?></td>
              <td><?= htmlspecialchars($t['transaction_date'] ?? '') ?></td>
              <td><?= htmlspecialchars($t['note'] ?? '') ?></td>

              <td class="text-nowrap">

                <a href="<?= url('/admin/finance/transaction/edit?id=' . $t['id']) ?>"
                   class="btn btn-sm btn-outline-secondary">
                  Sửa
                </a>

                <button type="button"
                        class="btn btn-sm btn-outline-secondary btn-delete-transaction"
                        data-id="<?= $t['id'] ?>">
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
     JS SECTION
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  // =========================
  // DELETE TRANSACTION
  // =========================
  document.querySelectorAll(".btn-delete-transaction").forEach(btn => {

    btn.addEventListener("click", async function () {

      const id = this.dataset.id;

      if (!confirm("Xóa giao dịch này?")) return;

      this.disabled = true;

      try {

        const res = await fetch("<?= url('/api/finance/transaction/delete') ?>", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
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

  // =========================
  // LOAD FILTER OPTIONS (API)
  // =========================
  async function loadOptions() {

    const params = new URLSearchParams(window.location.search);
    const selectedAccount = params.get("account_id");
    const selectedCategory = params.get("category_id");

    const [accRes, catRes] = await Promise.all([
      fetch("<?= url('/api/finance/account/list') ?>"),
      fetch("<?= url('/api/finance/category/list') ?>")
    ]);

    const accJson = await accRes.json();
    const catJson = await catRes.json();

    const accSelect = document.getElementById("accountSelect");
    const catSelect = document.getElementById("categorySelect");

    // ACCOUNTS
    (accJson.data || accJson.accounts || []).forEach(a => {
      const opt = document.createElement("option");
      opt.value = a.id;
      opt.textContent = a.name;

      if (selectedAccount && selectedAccount == a.id) {
        opt.selected = true;
      }

      accSelect.appendChild(opt);
    });

    // CATEGORIES
    (catJson.data || catJson.categories || []).forEach(c => {
      const opt = document.createElement("option");
      opt.value = c.id;
      opt.textContent = c.name;

      if (selectedCategory && selectedCategory == c.id) {
        opt.selected = true;
      }

      catSelect.appendChild(opt);
    });
  }

  // RUN
  loadOptions();

});
</script>