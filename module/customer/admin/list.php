<?php require_once PATH_ROOT . 'navbar.php'; ?>
<?php require_once PATH_CUSTOMER . 'service/customer.php'; ?>

<?php $result = customer_service(); ?>

<div class="container-fluid py-4 mt-5">

  <!-- FILTER -->
  <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

    <div class="d-flex gap-2">

      <input type="text"
             name="keyword"
             class="form-control form-control-sm"
             placeholder="Tìm theo tên / SĐT / email / địa chỉ"
             value="<?= htmlspecialchars($result['filters']['keyword'] ?? '') ?>">

      <!-- GROUP -->
      <select name="group" class="form-select form-select-sm">
        <option value="">Tất cả nhóm khách hàng</option>

        <?php foreach ($result['groups'] as $g): ?>
          <option value="<?= $g['id'] ?>"
            <?= ($result['filters']['group'] == $g['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($g['name']) ?>
          </option>
        <?php endforeach; ?>

      </select>

      <button type="submit" class="btn btn-sm btn-secondary">
        Lọc
      </button>

      <a href="<?= url('/admin/customer') ?>"
         class="btn btn-sm btn-outline-secondary">
        Xóa
      </a>

    </div>

    <div class="d-flex gap-2">

      <a href="<?= url('/admin/customer/create') ?>"
         class="btn btn-sm btn-secondary">
        Thêm khách hàng
      </a>

    </div>

  </form>

  <!-- TABLE -->
  <div class="table-responsive">

    <table class="table table-sm table-striped table-borderless align-middle mb-0">

      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Khách hàng</th>
          <th>Nhóm</th>
          <th>Liên hệ</th>
          <th>Email</th>
          <th>Địa chỉ</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($result['customers'])): ?>

          <tr>
            <td colspan="7" class="text-center text-muted py-3">
              Không tìm thấy khách hàng nào.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($result['customers'] as $index => $c): ?>

            <tr>

              <td>
                <?= customer_index($result['page'], $index) ?>
              </td>

              <td>
                <?= htmlspecialchars($c['name']) ?>
                <div class="text-muted small">
                  <?= htmlspecialchars($c['description'] ?? '') ?>
                </div>
              </td>

              <td>
                <?= customer_group_name($c['group_id'], $result['groups']) ?>
              </td>

              <td>
                <?= htmlspecialchars($c['phone'] ?? '') ?>
              </td>

              <td>
                <?= htmlspecialchars($c['email'] ?? '') ?>
              </td>

              <td>
                <?= htmlspecialchars($c['address'] ?? '') ?>
              </td>

              <td class="text-nowrap">

                <a href="<?= url('/admin/customer/edit?id=' . $c['id']) ?>"
                   class="btn btn-sm btn-outline-secondary">
                  Sửa
                </a>

                <a href="<?= url('/admin/customer/delete?id=' . $c['id']) ?>"
                   onclick="return confirm('Xóa khách hàng này?')"
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

<?php require_once PATH_ROOT . 'end.php'; ?>