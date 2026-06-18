<div class="container-fluid py-4 mt-5">
  <div class="card shadow-sm w-100">
    <div class="card-body">
      <h3 class="card-title mb-4">Chỉnh sửa khách hàng</h3>
      <form id="customer-edit-form" novalidate>
        <div class="row g-3">

          <div class="col-md-6">
            <label for="name" class="form-label">Tên khách hàng</label>
            <input type="text" class="form-control" id="name" required>
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
            <input type="text" class="form-control" id="phone">
            <small class="text-danger d-none" id="error-phone"></small>
          </div>

          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email">
            <small class="text-danger d-none" id="error-email"></small>
          </div>

          <div class="col-12">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="address" rows="3"></textarea>
            <small class="text-danger d-none" id="error-address"></small>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-secondary mt-3">Cập nhật khách hàng</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("customer-edit-form");
  const customerId = new URLSearchParams(window.location.search).get('id'); // Lấy id từ URL

  // Load nhóm khách hàng
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
    });

  // Load dữ liệu khách hàng hiện tại
  fetch(`/api/customer/show?id=${customerId}`)
    .then(res => res.json())
    .then(json => {
      if(json.success && json.data){
        const data = json.data;
        document.getElementById("name").value = data.name;
        document.getElementById("group_id").value = data.group_id;
        document.getElementById("phone").value = data.phone ?? '';
        document.getElementById("email").value = data.email ?? '';
        document.getElementById("address").value = data.address ?? '';
      } else {
        alert("Không tìm thấy khách hàng!");
        // window.location.href = "/customer";
      }
    });

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
      id: customerId,
      name: document.getElementById("name").value,
      group_id: document.getElementById("group_id").value,
      phone: document.getElementById("phone").value,
      email: document.getElementById("email").value,
      address: document.getElementById("address").value
    };

    if (!validate(payload)) return;

    try {
      const response = await fetch(`/api/customer/update`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const result = await response.json();
      if(result.success){
        alert("Cập nhật khách hàng thành công!");
        window.location.href = "/customer";
      } else {
        alert(result.message || "Cập nhật thất bại!");
        if(result.errors){
          Object.keys(result.errors).forEach(key => {
            const el = document.getElementById(`error-${key}`);
            if(el){
              el.textContent = result.errors[key][0];
              el.classList.remove('d-none');
            }
          });
        }
      }
    } catch(err) {
      console.error(err);
      alert("Có lỗi xảy ra, vui lòng thử lại.");
    }
  });

});
</script>