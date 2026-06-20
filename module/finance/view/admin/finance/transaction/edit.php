<?php require_once PATH_FINANCE . 'service/transaction.php'; ?>

<div class="container-fluid py-4 mt-5">

  <div class="card shadow-sm">
    <div class="card-body">

      <h3 class="mb-4">Sửa giao dịch</h3>

      <form id="form-edit">

        <input type="hidden" name="id" id="id">

        <div class="row g-3">

          <!-- ACCOUNT -->
          <div class="col-md-6">
            <label>Tài khoản</label>
            <select id="account_id" name="account_id" class="form-select"></select>
          </div>

          <!-- CATEGORY -->
          <div class="col-md-6">
            <label>Danh mục</label>
            <select id="category_id" name="category_id" class="form-select"></select>
          </div>

          <!-- MODULE -->
          <div class="col-md-6">
            <label>Module</label>
            <select id="module" name="module" class="form-select">
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>

          <!-- AMOUNT -->
          <div class="col-md-6">
            <label>Số tiền</label>
            <input id="amount" name="amount" class="form-control">
          </div>

          <!-- DATE -->
          <div class="col-md-6">
            <label>Ngày giao dịch</label>
            <input id="transaction_date" name="transaction_date" type="date" class="form-control">
          </div>

          <!-- NOTE -->
          <div class="col-12">
            <label>Ghi chú</label>
            <textarea id="note" name="note" class="form-control"></textarea>
          </div>

        </div>

        <div class="mt-3 text-end">
          <button class="btn btn-secondary">Cập nhật</button>
        </div>

      </form>

    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {

  const id = new URLSearchParams(window.location.search).get("id");

  if (!id) {
    alert("Missing ID");
    return;
  }

  // =========================
  // LOAD OPTIONS (ACCOUNT + CATEGORY)
  // =========================
  const [accRes, catRes] = await Promise.all([
    fetch("<?= url('/api/finance/account/list') ?>"),
    fetch("<?= url('/api/finance/category/list') ?>"),
  ]);

  const accJson = await accRes.json();
  const catJson = await catRes.json();

  const accSelect = document.getElementById("account_id");
  const catSelect = document.getElementById("category_id");

  accJson.data.forEach(a => {
    const opt = document.createElement("option");
    opt.value = a.id;
    opt.textContent = a.name; // 👈 show name
    accSelect.appendChild(opt);
  });

  catJson.data.forEach(c => {
    const opt = document.createElement("option");
    opt.value = c.id;
    opt.textContent = c.name; // 👈 show name
    catSelect.appendChild(opt);
  });

  // =========================
  // LOAD TRANSACTION DETAIL
  // =========================
  const res = await fetch("<?= url('/api/finance/transaction/show') ?>", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({id})
  });

  const json = await res.json();

  if (!json.success) {
    alert(json.message || "Không load được dữ liệu");
    return;
  }

  const d = json.data;

  document.getElementById("id").value = d.id;
  document.getElementById("account_id").value = d.account_id;
  document.getElementById("category_id").value = d.category_id;
  document.getElementById("module").value = d.module;
  document.getElementById("amount").value = d.amount;
  document.getElementById("transaction_date").value = d.transaction_date;
  document.getElementById("note").value = d.note || "";

});


// =========================
// UPDATE
// =========================
document.getElementById("form-edit").addEventListener("submit", async e => {
  e.preventDefault();

  const payload = Object.fromEntries(new FormData(e.target));

  const res = await fetch("<?= url('/api/finance/transaction/update') ?>", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify(payload)
  });

  const json = await res.json();

  if (json.success) {
    window.location.href = "<?= url('/admin/finance/transaction') ?>";
  } else {
    alert(json.message || "Lỗi");
  }
});
</script>