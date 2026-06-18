<div class="container-fluid py-4 mt-5">
  <div class="card shadow-sm w-100">
    <div class="card-body">
      <h3 class="card-title mb-4">Thêm khách hàng</h3>
      <form id="customer-add-form" novalidate>
        <div class="row g-3">

          <div class="col-md-6">
            <label for="name" class="form-label">Tên khách hàng</label>
            <input type="text" class="form-control" id="name" placeholder="Nhập tên khách hàng" required>
            <small class="text-danger d-none" id="error-name"></small>
          </div>

          <div class="col-md-6">
            <label for="group_id" class="form-label">Nhóm khách hàng</label>
            <select class="form-select" id="group_id" required>
              <option value="">Chọn nhóm khách hàng</option>
            </select>
            <small class="text-danger d-none" id="error-group"></small>
          </div>

          <div class="col-md-6">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" placeholder="Nhập số điện thoại">
            <small class="text-danger d-none" id="error-phone"></small>
          </div>

          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Nhập email">
            <small class="text-danger d-none" id="error-email"></small>
          </div>

          <div class="col-12">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="address" rows="3" placeholder="Nhập địa chỉ"></textarea>
            <small class="text-danger d-none" id="error-address"></small>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-secondary mt-3">Thêm khách hàng</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("customer-add-form");

  // Load nhóm khách hàng từ API
  fetch("/api/customer-group/list")
    .then(res => res.json())
    .then(json => {
      const select = document.getElementById("group_id");
      if(json.success && Array.isArray(json.data)) {
        json.data.forEach(group => {
          const option = document.createElement("option");
          option.value = group.id;
          option.textContent = group.name;
          select.appendChild(option);
        });
      }
    })
    .catch(err => console.error("Lỗi load nhóm khách hàng:", err));

  function clearErrors() {
    document.querySelectorAll('small.text-danger').forEach(el => {
      el.textContent = '';
      el.classList.add('d-none');
    });
  }

  function validate(payload) {
    let isValid = true;
    clearErrors();

    if (!payload.name.trim()) {
      const el = document.getElementById('error-name');
      el.textContent = "Tên khách hàng không được để trống.";
      el.classList.remove('d-none');
      isValid = false;
    }

    if (!payload.group_id) {
      const el = document.getElementById('error-group');
      el.textContent = "Vui lòng chọn nhóm khách hàng.";
      el.classList.remove('d-none');
      isValid = false;
    }

    return isValid;
  }

  form.addEventListener("submit", async function(e) {
    e.preventDefault();

    const payload = {
      name: document.getElementById("name").value,
      group_id: document.getElementById("group_id").value,
      phone: document.getElementById("phone").value,
      email: document.getElementById("email").value,
      address: document.getElementById("address").value
    };

    if (!validate(payload)) return;

    try {
      const response = await fetch("/api/customer/create", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const result = await response.json();
      if(result.success){
        alert("Thêm khách hàng thành công!");
        window.location.href = "/customer"; // redirect về danh sách
      } else {
        alert(result.message || "Thêm khách hàng thất bại!");
      }
    } catch(err) {
      console.error(err);
      alert("Có lỗi xảy ra, vui lòng thử lại.");
    }
  });
});
</script>