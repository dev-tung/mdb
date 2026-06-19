<?php require_once PATH_ROOT . 'shop/admin/header.php'; ?>
<?php require_once PATH_SHOP . 'service/product.php'; ?>

<?php $result = product_service(); ?>

<div class="container-fluid py-4 mt-5">

  <!-- FILTER -->
  <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">

      <input type="text"
            name="keyword"
            class="form-control form-control-sm"
            placeholder="Tìm theo tên sản phẩm"
            value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">

      <!-- CATEGORY -->
      <select name="category" class="form-select form-select-sm">
        <option value="">Tất cả loại sản phẩm</option>

        <?php foreach ($result['categories'] as $c): ?>
          <option value="<?= $c['id'] ?>"
            <?= (($_GET['category'] ?? '') == $c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>

      </select>

      <!-- STATUS -->
      <select name="status" class="form-select form-select-sm w-auto">
        <option value="">Tất cả trạng thái</option>
        <option value="1" <?= (($_GET['status'] ?? '') == '1') ? 'selected' : '' ?>>Đang bán</option>
        <option value="0" <?= (($_GET['status'] ?? '') == '0') ? 'selected' : '' ?>>Ẩn</option>
      </select>

      <button type="submit" class="btn btn-sm btn-secondary">
        Lọc
      </button>

      <!-- RESET FILTER (ĐÚNG CÁCH) -->
      <a href="<?= url('/admin/product') ?>" class="btn btn-sm btn-outline-secondary">
        Xóa
      </a>

    </div>

    <div class="d-flex gap-2">
      <a href="<?= url('/product/create'); ?>"
        class="btn btn-sm btn-secondary">
        Thêm sản phẩm
      </a>
    </div>

  </form>

  <!-- TABLE -->
  <div class="table-responsive">
    <table class="table table-sm table-striped table-borderless align-middle mb-0">

      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tên sản phẩm</th>
          <th>Loại</th>
          <th>Giá gốc</th>
          <th>Ngày cập nhật</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($result['products'])): ?>
            <tr>
                <td colspan="7" class="text-center text-muted py-3">
                    Không tìm thấy sản phẩm nào.
                </td>
            </tr>
        <?php else: ?>

            <?php foreach ($result['products'] as $index => $p): ?>

                <tr>
                    <td><?= product_index($result['page'], $index) ?></td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= product_image($p['thumbnail']) ?>"
                                class="rounded"
                                style="width:35px;height:35px;object-fit:cover">
                            <?= htmlspecialchars($p['name']) ?>
                        </div>
                    </td>

                    <td><?= product_category_name($p['category_id'], $result['categories']) ?></td>
                    <td><?= product_price($p['price']) ?></td>
                    <td><?= product_updated_at($p) ?></td>
                    <td><?= product_status($p['status']) ?></td>

                    <td>
                        <a href="<?= url('/product/edit?id=' . $p['id']) ?>"
                          class="btn btn-sm btn-outline-secondary">Sửa</a>

                        <a href="<?= url('/product/delete?id=' . $p['id']) ?>"
                          onclick="return confirm('Xóa sản phẩm này?')"
                          class="btn btn-sm btn-outline-secondary">Xóa</a>
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

<?php require_once PATH_ROOT . 'end.php'; ?>