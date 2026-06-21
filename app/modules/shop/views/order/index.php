<div class="container-fluid py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">

  <div class="row g-2">

      <div class="col-auto">
          <input
              type="text"
              id="filter-name"
              class="form-control form-control-sm"
              placeholder="Tìm theo tên khách hàng">
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
          </select>
      </div>

      <div class="col-auto">
          <select
              id="filter-payment"
              class="form-select form-select-sm">
              <option value="">Thanh toán</option>
          </select>
      </div>

  </div>

      <a href="#" class="btn btn-sm btn-outline-secondary">
          Tạo đơn bán hàng
      </a>

  </div>

  <div class="mb-3">
      <strong>Tổng tiền:</strong>
      <span id="total-amount">12.500.000 ₫</span>
  </div>

  <div class="table-responsive">

      <table class="table table-sm align-middle">

          <thead>
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

          <tbody id="customer-table-body">

              <tr>
                  <td>1</td>
                  <td>Nguyễn Văn A</td>
                  <td>Bán lẻ</td>
                  <td>2.500.000 ₫</td>

                  <td>
                      <select class="form-select form-select-sm">
                          <option>Mới</option>
                          <option selected>Đang xử lý</option>
                          <option>Hoàn thành</option>
                      </select>
                  </td>

                  <td>
                      <select class="form-select form-select-sm">
                          <option selected>Đã thanh toán</option>
                          <option>Chưa thanh toán</option>
                      </select>
                  </td>

                  <td>21/06/2026</td>

                  <td>
                      <a href="#" class="btn btn-sm btn-outline-secondary">
                          Sửa
                      </a>

                      <button class="btn btn-sm btn-outline-secondary">
                          Xóa
                      </button>
                  </td>
              </tr>

              <tr>
                  <td>2</td>
                  <td>Trần Thị B</td>
                  <td>CLB</td>
                  <td>5.000.000 ₫</td>

                  <td>
                      <select class="form-select form-select-sm">
                          <option>Mới</option>
                          <option>Đang xử lý</option>
                          <option selected>Hoàn thành</option>
                      </select>
                  </td>

                  <td>
                      <select class="form-select form-select-sm">
                          <option>Đã thanh toán</option>
                          <option selected>Chưa thanh toán</option>
                      </select>
                  </td>

                  <td>20/06/2026</td>

                  <td>
                      <a href="#" class="btn btn-sm btn-outline-secondary">
                          Sửa
                      </a>

                      <button class="btn btn-sm btn-outline-secondary">
                          Xóa
                      </button>
                  </td>
              </tr>

          </tbody>

      </table>

  </div>

  <nav class="mt-3">

      <ul class="pagination pagination-sm" id="pagination">

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  Đầu
              </a>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  1
              </a>
          </li>

          <li class="page-item disabled">
              <span class="page-link">
                  ...
              </span>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  8
              </a>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  9
              </a>
          </li>

          <li class="page-item active">
              <a
                  class="page-link text-secondary bg-light border-secondary"
                  href="#">
                  10
              </a>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  11
              </a>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  12
              </a>
          </li>

          <li class="page-item disabled">
              <span class="page-link">
                  ...
              </span>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  50
              </a>
          </li>

          <li class="page-item">
              <a class="page-link text-secondary" href="#">
                  Cuối
              </a>
          </li>

      </ul>

  </nav>
</div>
