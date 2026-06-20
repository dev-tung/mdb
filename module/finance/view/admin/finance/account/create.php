<?php require_once PATH_FINANCE . 'service/account.php'; ?>

<div class="container-fluid py-4 mt-5">

  <div class="card shadow-sm w-100">
    <div class="card-body">

      <h3 class="card-title mb-4">Thêm tài khoản tài chính</h3>

      <form id="finance-account-create-form">

        <div class="row g-3">

          <!-- NAME -->
          <div class="col-md-6">
            <label class="form-label">Tên tài khoản</label>
            <input type="text" name="name" class="form-control" placeholder="VD: Tiền mặt, Vietcombank...">
            <small class="text-danger d-none" id="error-name"></small>
          </div>

          <!-- TYPE -->
          <div class="col-md-6">
            <label class="form-label">Loại tài khoản</label>
            <select name="type" class="form-select">
              <option value="cash">Tiền mặt</option>
              <option value="bank">Ngân hàng</option>
              <option value="wallet">Ví điện tử</option>
              <option value="debt">Công nợ</option>
            </select>
          </div>

          <!-- INITIAL BALANCE -->
          <div class="col-md-6">
            <label class="form-label">Số dư ban đầu</label>
            <input type="number" name="initial_balance" class="form-control" value="0" min="0">
            <small class="text-danger d-none" id="error-initial_balance"></small>
          </div>

          <!-- STATUS -->
          <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="1">Hoạt động</option>
              <option value="0">Tạm khóa</option>
            </select>
          </div>

          <!-- NOTE -->
          <div class="col-12">
            <label class="form-label">Ghi chú</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
          </div>

          <!-- BUTTON -->
          <div class="col-12 text-end mt-3">

            <a href="<?= url('/admin/finance/account') ?>" class="btn btn-outline-secondary">
              Hủy
            </a>

            <button type="submit" id="submitBtn" class="btn btn-secondary">
              Lưu tài khoản
            </button>

          </div>

        </div>

      </form>

    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const form = document.getElementById("finance-account-create-form");
  const btn = document.getElementById("submitBtn");

  if (!form) {
    console.error("FORM NOT FOUND");
    return;
  }

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    console.log("SUBMIT FIRED");

    btn.disabled = true;
    btn.innerText = "Đang lưu...";

    // reset errors
    document.querySelectorAll("[id^='error-']").forEach(el => {
      el.classList.add("d-none");
      el.textContent = "";
    });

    // ✅ SAFE FORM DATA (KHÔNG CRASH)
    const formData = new FormData(form);

    const payload = {
      name: formData.get("name") || "",
      type: formData.get("type") || "cash",
      initial_balance: formData.get("initial_balance") || 0,
      status: formData.get("status") || 1,
      note: formData.get("note") || ""
    };

    console.log("PAYLOAD:", payload);

    try {

      const res = await fetch("<?= url('/api/finance/account/create') ?>", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });

      const text = await res.text(); // ✅ chống JSON crash
      console.log("RAW RESPONSE:", text);

      let json;

      try {
        json = JSON.parse(text);
      } catch (e) {
        throw new Error("Server không trả JSON hợp lệ");
      }

      if (!res.ok || !json.success) {

        if (json.errors) {
          Object.keys(json.errors).forEach(key => {
            const el = document.getElementById("error-" + key);
            if (el) {
              el.textContent = json.errors[key][0];
              el.classList.remove("d-none");
            }
          });
        } else {
          alert(json.message || "Có lỗi xảy ra!");
        }

        btn.disabled = false;
        btn.innerText = "Lưu tài khoản";
        return;
      }

      alert("Tạo tài khoản thành công!");
      window.location.href = "<?= url('/admin/finance/account') ?>";

    } catch (err) {
      console.error(err);
      alert("Lỗi hệ thống!");
      btn.disabled = false;
      btn.innerText = "Lưu tài khoản";
    }

  });

});
</script>