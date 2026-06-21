<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input
          type="text"
          id="filter-name"
          class="form-control form-control-sm"
          placeholder="Tìm theo tên sản phẩm">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-from"
          class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-to"
          class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <select
          id="filter-status"
          class="form-select form-select-sm">
          <option value="">Trạng thái</option>
          <option value="active">Đang bán</option>
          <option value="inactive">Ngừng bán</option>
        </select>
      </div>

      <div class="col-auto">
        <select
          id="filter-category"
          class="form-select form-select-sm">
          <option value="">Danh mục</option>
        </select>
      </div>

    </div>

    <a href="#" class="btn btn-sm btn-outline-secondary">
      Thêm sản phẩm
    </a>

  </div>

  <div class="mb-3">
    <strong>Tổng sản phẩm:</strong>
    <span id="total-amount">2</span>
  </div>

  <div class="table-responsive">

    <table class="table table-sm align-middle">

      <thead>
        <tr>
          <th>#</th>
          <th>Sản phẩm</th>
          <th>Danh mục</th>
          <th>Giá bán</th>
          <th>Tồn kho</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody id="product-table-body">

      <?php if (!empty($products)): ?>
          <?php foreach ($products as $index => $product): ?>

              <tr>
                  <td><?= $index + 1 ?></td>

                  <td>
                      <?= htmlspecialchars($product['name'] ?? '') ?>
                  </td>

                  <td>
                      <?= htmlspecialchars($product['category_name'] ?? '---') ?>
                  </td>

                  <td>
                      <?= number_format($product['price'] ?? 0, 0, ',', '.') ?> ₫
                  </td>

                  <td>
                      <?= (int) ($product['stock'] ?? 0) ?>
                  </td>

                  <td>
                      <select class="form-select form-select-sm">
                          <option value="active" <?= ($product['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                              Đang bán
                          </option>

                          <option value="inactive" <?= ($product['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                              Ngừng bán
                          </option>
                      </select>
                  </td>

                  <td>
                      <?= !empty($product['created_at'])
                          ? date('d/m/Y', strtotime($product['created_at']))
                          : '' ?>
                  </td>

                  <td>
                      <a href="/product/edit?id=<?= $product['id'] ?>"
                        class="btn btn-sm btn-outline-secondary">
                          Sửa
                      </a>

                      <button
                          class="btn btn-sm btn-outline-secondary"
                          data-id="<?= $product['id'] ?>">
                          Xóa
                      </button>
                  </td>
              </tr>

          <?php endforeach; ?>
      <?php else: ?>

          <tr>
              <td colspan="8" class="text-center text-muted">
                  Không có sản phẩm nào
              </td>
          </tr>

      <?php endif; ?>

      </tbody>

    </table>

  </div>

  <nav class="mt-3">

    <ul class="pagination pagination-sm" id="pagination">

      <!-- FIRST -->
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link text-secondary" href="?page=1">Đầu</a>
      </li>

      <!-- PREV -->
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link text-secondary" href="?page=<?= $page - 1 ?>">Trước</a>
      </li>

      <!-- PAGES -->
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>

        <?php if (
          $i == 1 ||
          $i == $totalPages ||
          ($i >= $page - 2 && $i <= $page + 2)
        ): ?>

          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link text-secondary <?= ($i == $page) ? 'bg-light border-secondary' : '' ?>"
              href="?page=<?= $i ?>">
              <?= $i ?>
            </a>
          </li>

        <?php elseif ($i == 2 || $i == $totalPages - 1): ?>

          <li class="page-item disabled">
            <span class="page-link">...</span>
          </li>

        <?php endif; ?>

      <?php endfor; ?>

      <!-- NEXT -->
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link text-secondary" href="?page=<?= $page + 1 ?>">Sau</a>
      </li>

      <!-- LAST -->
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link text-secondary" href="?page=<?= $totalPages ?>">Cuối</a>
      </li>

    </ul>

  </nav>
</div>