<?php require_once PATH_FINANCE . 'service/account.php'; ?>

<div class="container-fluid py-4 mt-5">

  <div class="card shadow-sm w-100">
    <div class="card-body">

      <h3 class="card-title mb-4">Chỉnh sửa tài khoản tài chính</h3>

      <form id="finance-account-edit-form">

        <input type="hidden" id="id" name="id">

        <div class="row g-3">

          <!-- NAME -->
          <div class="col-md-6">
            <label class="form-label">Tên tài khoản</label>
            <input type="text" id="name" name="name" class="form-control">
            <small class="text-danger d-none" id="error-name"></small>
          </div>

          <!-- TYPE -->
          <div class="col-md-6">
            <label class="form-label">Loại tài khoản</label>
            <select id="type" name="type" class="form-select">
              <option value="cash">Tiền mặt</option>
              <option value="bank">Ngân hàng</option>
              <option value="wallet">Ví điện tử</option>
              <option value="debt">Công nợ</option>
            </select>
          </div>

          <!-- INITIAL BALANCE -->
          <div class="col-md-6">
            <label class="form-label">Số dư ban đầu</label>
            <input type="number" id="initial_balance" name="initial_balance" class="form-control">
            <small class="text-danger d-none" id="error-initial_balance"></small>
          </div>

          <!-- STATUS -->
          <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select id="status" name="status" class="form-select">
              <option value="1">Hoạt động</option>
              <option value="0">Tạm khóa</option>
            </select>
          </div>

          <!-- NOTE -->
          <div class="col-12">
            <label class="form-label">Ghi chú</label>
            <textarea id="note" name="note" class="form-control" rows="3"></textarea>
          </div>

          <!-- BUTTON -->
          <div class="col-12 text-end mt-3">

            <a href="<?= url('/admin/finance/account') ?>" class="btn btn-outline-secondary">
              Hủy
            </a>

            <button type="submit" id="submitBtn" class="btn btn-secondary">
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

  const form = document.getElementById("finance-account-edit-form");
  const btn = document.getElementById("submitBtn");

  const id = new URLSearchParams(window.location.search).get("id");

  if (!id) {
    alert("Missing ID");
    return;
  }

  // =========================
  // LOAD DATA (SHOW API)
  // =========================
  try {

    const res = await fetch("<?= url('/api/finance/account/show') ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id })
    });

    const json = await res.json();

    if (!json.success) {
      alert(json.message || "Không tìm thấy tài khoản");
      return;
    }

    const data = json.data;

    document.getElementById("id").value = data.id;
    document.getElementById("name").value = data.name;
    document.getElementById("type").value = data.type;
    document.getElementById("initial_balance").value = data.initial_balance;
    document.getElementById("status").value = data.status;
    document.getElementById("note").value = data.note || "";

  } catch (err) {
    console.error(err);
    alert("Lỗi load dữ liệu");
  }

  // =========================
  // UPDATE SUBMIT
  // =========================
  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    btn.disabled = true;
    btn.innerText = "Đang cập nhật...";

    const payload = {
      id: document.getElementById("id").value,
      name: document.getElementById("name").value,
      type: document.getElementById("type").value,
      initial_balance: document.getElementById("initial_balance").value,
      status: document.getElementById("status").value,
      note: document.getElementById("note").value
    };

    try {

      const res = await fetch("<?= url('/api/finance/account/update') ?>", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });

      const json = await res.json();

      if (!json.success) {
        alert(json.message || "Cập nhật thất bại!");
        btn.disabled = false;
        btn.innerText = "Cập nhật";
        return;
      }

      alert("Cập nhật thành công!");
      window.location.href = "<?= url('/admin/finance/account') ?>";

    } catch (err) {
      console.error(err);
      alert("Lỗi hệ thống!");
      btn.disabled = false;
      btn.innerText = "Cập nhật";
    }

  });

});
</script>