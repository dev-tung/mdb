<div class="container-fluid py-4 mt-5">

  <div class="card shadow-sm">
    <div class="card-body">

      <h3 class="mb-4">Thêm giao dịch</h3>

      <form id="form-create">

        <div class="row g-3">

          <!-- ACCOUNT (API) -->
          <div class="col-md-6">
            <label>Tài khoản</label>
            <select name="account_id" id="accountSelect" class="form-select">
              <option value="">-- Chọn tài khoản --</option>
            </select>
          </div>

          <!-- CATEGORY (API) -->
          <div class="col-md-6">
            <label>Danh mục</label>
            <select name="category_id" id="categorySelect" class="form-select">
              <option value="">-- Chọn danh mục --</option>
            </select>
          </div>

          <div class="col-md-6">
            <label>Module</label>
            <select name="module" class="form-select">
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>

          <div class="col-md-6">
            <label>Amount</label>
            <input name="amount" type="number" class="form-control">
          </div>

          <div class="col-md-6">
            <label>Date</label>
            <input name="transaction_date" type="date" class="form-control">
          </div>

          <div class="col-12">
            <label>Note</label>
            <textarea name="note" class="form-control"></textarea>
          </div>

        </div>

        <div class="mt-3 text-end">
          <button class="btn btn-secondary">Lưu</button>
        </div>

      </form>

    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  // =========================
  // LOAD ACCOUNT + CATEGORY
  // =========================
  async function loadOptions() {

    const [accRes, catRes] = await Promise.all([
      fetch("<?= url('/api/finance/account/list') ?>"),
      fetch("<?= url('/api/finance/category/list') ?>")
    ]);

    const accJson = await accRes.json();
    const catJson = await catRes.json();

    const accSelect = document.getElementById("accountSelect");
    const catSelect = document.getElementById("categorySelect");

    (accJson.data || accJson.accounts || []).forEach(a => {
      const opt = document.createElement("option");
      opt.value = a.id;
      opt.textContent = a.name;
      accSelect.appendChild(opt);
    });

    (catJson.data || catJson.categories || []).forEach(c => {
      const opt = document.createElement("option");
      opt.value = c.id;
      opt.textContent = c.name;
      catSelect.appendChild(opt);
    });
  }

  loadOptions();

  // =========================
  // SUBMIT FORM
  // =========================
  document.getElementById("form-create").addEventListener("submit", async e => {
    e.preventDefault();

    const payload = Object.fromEntries(new FormData(e.target));

    const res = await fetch("<?= url('/api/finance/transaction/create') ?>", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const json = await res.json();

    if (json.success) {
      window.location.href = "<?= url('/admin/finance/transaction') ?>";
    } else {
      alert(json.message || "Lỗi");
    }
  });

});
</script>