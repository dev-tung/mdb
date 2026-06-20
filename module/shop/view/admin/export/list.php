
<?php require_once PATH_SHOP. 'service/shop.php'; ?>
<?php require_once PATH_SHOP . 'service/export.php'; ?>
<?php $result = export_service(); ?>

<div class="container-fluid py-4 mt-5">

    <form method="GET" class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex gap-2">

            <input type="text"
                   name="keyword"
                   class="form-control form-control-sm"
                   placeholder="Tìm theo tên khách hàng"
                   value="<?= htmlspecialchars($result['ctx']['keyword']) ?>">

            <input type="date"
                   name="from"
                   class="form-control form-control-sm"
                   value="<?= $result['ctx']['from'] ?>">

            <input type="date"
                   name="to"
                   class="form-control form-control-sm"
                   value="<?= $result['ctx']['to'] ?>">

            <select name="status" class="form-select form-select-sm">

                <option value="">Trạng thái</option>

                <?php foreach (shop_option('product_status') as $key => $label): ?>
                    <option value="<?= $key ?>"
                        <?= $result['ctx']['status'] == $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <select name="payment" class="form-select form-select-sm">

                <option value="">Thanh toán</option>

                <?php foreach (shop_option('payment_status') as $key => $label): ?>
                    <option value="<?= $key ?>"
                        <?= $result['ctx']['payment'] == $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <button type="submit" class="btn btn-sm btn-secondary">
                Lọc
            </button>

            <a href="<?= url('/admin/export') ?>"
               class="btn btn-sm btn-outline-secondary">
                Xóa
            </a>

        </div>

        <a href="<?= url('/admin/export/create') ?>"
           class="btn btn-sm btn-secondary">
            Tạo đơn bán hàng
        </a>

    </form>

    <div class="mb-3">
        <strong>
            Tổng tiền
            <?= number_format($result['totalAmount']) ?> ₫
        </strong>
    </div>

    <div class="table-responsive">

        <table class="table table-sm table-striped table-borderless align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Nhóm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($result['exports'])): ?>

                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">
                            Không có dữ liệu
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($result['exports'] as $index => $item): ?>

                        <tr>

                            <td>
                                <?= (($result['page'] - 1) * 100) + $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($item['customer_name']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($item['customer_group'] ?? '-') ?>
                            </td>

                            <td>
                                <?= number_format($item['total_amount']) ?> ₫
                            </td>

                            <td>
                                <select
                                    class="form-select form-select-sm update-status <?= $item['status'] !== 'completed' ? 'text-danger' : '' ?>"
                                    data-id="<?= $item['id'] ?>"
                                    style="min-width:150px">

                                    <?php foreach (shop_option('product_status') as $key => $label): ?>
                                        <option value="<?= $key ?>"
                                            <?= $item['status'] === $key ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </td>

                            <td>
                                <select
                                    class="form-select form-select-sm update-payment <?= $item['payment_status'] !== 'paid' ? 'text-danger' : '' ?>"
                                    data-id="<?= $item['id'] ?>"
                                    style="min-width:150px">

                                    <?php foreach (shop_option('payment_status') as $key => $label): ?>
                                        <option value="<?= $key ?>"
                                            <?= $item['payment_status'] === $key ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </td>

                            <td>
                                <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                            </td>

                            <td>

                                <a href="<?= url('/admin/export/edit?id=' . $item['id']) ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    Sửa
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary btn-delete"
                                    data-id="<?= $item['id'] ?>">
                                    Xóa
                                </button>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <?= pager([
        'page'  => $result['page'],
        'total' => $result['totalPages'],
        'query' => $_GET
    ]) ?>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.update-status')
        .forEach(select => {

            select.addEventListener('change', async function () {

                try {

                    const response = await fetch(
                        '<?= url("/api/export/status") ?>?id=' + this.dataset.id,
                        {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: this.value
                            })
                        }
                    );

                    const result = await response.json();

                    if (!result.success) {
                        alert(result.message || 'Cập nhật thất bại');
                        return;
                    }

                    this.classList.toggle(
                        'text-danger',
                        this.value !== 'completed'
                    );

                } catch (e) {
                    alert('Có lỗi xảy ra');
                }

            });

        });

    document.querySelectorAll('.update-payment')
        .forEach(select => {

            select.addEventListener('change', async function () {

                try {

                    const response = await fetch(
                        '<?= url("/api/export/payment") ?>?id=' + this.dataset.id,
                        {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                payment_status: this.value
                            })
                        }
                    );

                    const result = await response.json();

                    if (!result.success) {
                        alert(result.message || 'Cập nhật thất bại');
                        return;
                    }

                    this.classList.toggle(
                        'text-danger',
                        this.value !== 'paid'
                    );

                } catch (e) {
                    alert('Có lỗi xảy ra');
                }

            });

        });

        document.querySelectorAll('.btn-delete')
        .forEach(button => {

            button.addEventListener('click', async function () {

                if (!confirm('Xóa đơn hàng này?')) {
                    return;
                }

                try {

                    const response = await fetch(
                        '<?= url("/api/export/delete") ?>?id=' + this.dataset.id,
                        {
                            method: 'DELETE'
                        }
                    );

                    const result = await response.json();

                    if (!result.success) {
                        alert(result.message || 'Xóa thất bại');
                        return;
                    }

                    this.closest('tr')?.remove();

                    alert('Xóa thành công');

                } catch (e) {

                    alert('Có lỗi xảy ra');
                }

            });

        });

});
</script>