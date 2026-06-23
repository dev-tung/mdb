<div class="container-fluid py-4 mt-5">

    <h3 class="mb-4">Thêm sản phẩm</h3>

    <form id="product-create-form" novalidate>

        <div class="row g-3">

            <!-- NAME -->
            <div class="col-md-6">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" id="name" class="form-control" placeholder="Nhập tên sản phẩm">
            </div>

            <!-- PRICE -->
            <div class="col-md-6">
                <label class="form-label">Giá</label>
                <input type="number" id="price" class="form-control" placeholder="Nhập giá">
            </div>

            <!-- CATEGORY -->
            <div class="col-md-6">
                <label class="form-label">Danh mục</label>
                <select id="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                </select>
            </div>

            <!-- THUMBNAIL (FIX HERE) -->
            <div class="col-md-6">
                <label class="form-label">Ảnh sản phẩm</label>
                <input type="file" id="thumbnail" class="form-control">
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-12">
                <label class="form-label">Mô tả</label>
                <textarea id="description" class="form-control" placeholder="Nhập mô tả"></textarea>
            </div>

            <!-- SUBMIT -->
            <div class="col-12">
                <button type="submit" class="btn btn-outline-secondary mt-3">
                    Thêm sản phẩm
                </button>
            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // LOAD CATEGORIES
    // =========================
    async function loadCategories() {
        try {
            const res = await fetch('/api/categories');
            const json = await res.json();

            const data = json.data;

            const select = document.querySelector('#category_id');

            select.innerHTML = '<option value="">-- Chọn danh mục --</option>';

            data.forEach(c => {
                select.innerHTML += `
                    <option value="${c.id}">
                        ${c.name}
                    </option>
                `;
            });

        } catch (err) {
            console.error(err);
        }
    }


    // =========================
    // CREATE PRODUCT
    // =========================
    document.querySelector('#product-create-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();

        formData.append('name', document.querySelector('#name').value);
        formData.append('price', document.querySelector('#price').value);
        formData.append('category_id', document.querySelector('#category_id').value);
        formData.append('description', document.querySelector('#description').value);

        // FIX: image -> thumbnail
        const thumbnail = document.querySelector('#thumbnail').files[0];
        if (thumbnail) {
            formData.append('thumbnail', thumbnail);
        }

        try {
            const res = await fetch('/api/products', {
                method: 'POST',
                body: formData
            });

            const result = await res.json();

            if (result.success) {
                alert('Thêm sản phẩm thành công');
                window.location.href = '/admin/products';
            } else {
                alert(result.message || 'Thêm thất bại');
            }

        } catch (err) {
            console.error(err);
            alert('Lỗi hệ thống');
        }
    });


    // =========================
    // INIT
    // =========================
    loadCategories();

}
</script>