<?php require_once PATH_FINANCE . 'service/account.php'; ?>

<?php $result = finance_account_service(); ?>

<div class="container-fluid py-4 mt-5">

  <!-- FILTER -->
  <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">

      <input type="text"
             name="keyword"
             class="form-control form-control-sm"
             placeholder="Tìm theo tên / type / ghi chú"
             value="<?= htmlspecialchars($result['filters']['keyword'] ?? '') ?>">

      <!-- TYPE -->
      <select name="type" class="form-select form-select-sm">
        <option value="">Tất cả loại tài khoản</option>

        <option value="cash"   <?= ($result['filters']['type'] ?? '') == 'cash' ? 'selected' : '' ?>>Tiền mặt</option>
        <option value="bank"   <?= ($result['filters']['type'] ?? '') == 'bank' ? 'selected' : '' ?>>Ngân hàng</option>
        <option value="wallet" <?= ($result['filters']['type'] ?? '') == 'wallet' ? 'selected' : '' ?>>Ví</option>
        <option value="debt"   <?= ($result['filters']['type'] ?? '') == 'debt' ? 'selected' : '' ?>>Công nợ</option>

      </select>

      <!-- STATUS -->
      <select name="status" class="form-select form-select-sm">
        <option value="-1">Tất cả trạng thái</option>
        <option value="1" <?= ($result['filters']['status'] ?? -1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
        <option value="0" <?= ($result['filters']['status'] ?? -1) == 0 ? 'selected' : '' ?>>Tạm khóa</option>
      </select>

      <button type="submit" class="btn btn-sm btn-secondary">
        Lọc
      </button>

      <a href="<?= url('/admin/finance/account') ?>"
         class="btn btn-sm btn-outline-secondary">
        Xóa
      </a>

    </div>

    <div class="d-flex gap-2">

      <a href="<?= url('/admin/finance/account/create') ?>"
         class="btn btn-sm btn-secondary">
        Thêm tài khoản
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
          <th>Loại</th>
          <th>Số dư ban đầu</th>
          <th>Ghi chú</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($result['accounts'])): ?>

          <tr>
            <td colspan="7" class="text-center text-muted py-3">
              Không tìm thấy tài khoản nào.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($result['accounts'] as $index => $a): ?>

            <tr>

              <td>
                <?= finance_account_index($result['page'], $index) ?>
              </td>

              <td>
                <?= htmlspecialchars($a['name'] ?? '') ?>
              </td>

              <td>
                <?= htmlspecialchars($a['type'] ?? '') ?>
              </td>

              <td>
                <?= number_format((float)($a['initial_balance'] ?? 0)) ?>
              </td>

              <td>
                <?= htmlspecialchars($a['note'] ?? '') ?>
              </td>

              <td>
                <?php if (($a['status'] ?? 0) == 1): ?>
                  <span class="badge bg-success">Hoạt động</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Tạm khóa</span>
                <?php endif; ?>
              </td>

              <td class="text-nowrap">

                <a href="<?= url('/admin/finance/account/edit?id=' . $a['id']) ?>"
                   class="btn btn-sm btn-outline-secondary">
                  Sửa
                </a>

                <a href="<?= url('/admin/finance/account/delete?id=' . $a['id']) ?>"
                   onclick="return confirm('Xóa tài khoản này?')"
                   class="btn btn-sm btn-outline-secondary">
                  Xóa
                </a>

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