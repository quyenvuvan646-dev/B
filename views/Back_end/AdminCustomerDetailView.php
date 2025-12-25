<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col-12">
      <a href="<?= APP_URL ?>/Admin/users" class="btn btn-sm btn-outline-light mb-3">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
      <h3 style="color: #f8fafc;">
        <i class="bi bi-person-circle"></i> Thông tin khách hàng
      </h3>
    </div>
  </div>

  <div class="row">
    <!-- Customer Info Card -->
    <div class="col-12 col-lg-6 mb-4">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body" style="color: #cbd5e1;">
          <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
            <i class="bi bi-card-text"></i> Thông tin cơ bản
          </h5>
          
          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Tên khách hàng</label>
            <p style="color: #f8fafc; font-weight: 600;">
              <?= htmlspecialchars($customer['fullname'] ?? 'N/A') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Email</label>
            <p style="color: #f8fafc; word-break: break-all;">
              <?= htmlspecialchars($customer['email'] ?? '') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Điện thoại</label>
            <p style="color: #f8fafc;">
              <?= htmlspecialchars($customer['phone'] ?? 'Chưa cập nhật') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Địa chỉ</label>
            <p style="color: #f8fafc;">
              <?= htmlspecialchars($customer['address'] ?? 'Chưa cập nhật') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Ngày tạo tài khoản</label>
            <p style="color: #f8fafc;">
              <?= !empty($customer['created_at']) ? date('d/m/Y H:i', strtotime($customer['created_at'])) : 'N/A' ?>
            </p>
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label style="color: #94a3b8; font-size: 0.9rem;">Xác minh</label>
              <p>
                <?php if ($customer['is_verified']): ?>
                  <span class="badge bg-success">Đã xác minh</span>
                <?php else: ?>
                  <span class="badge bg-warning">Chưa xác minh</span>
                <?php endif; ?>
              </p>
            </div>
            <div class="col-6">
              <label style="color: #94a3b8; font-size: 0.9rem;">Trạng thái</label>
              <p>
                <?php if ($customer['is_locked']): ?>
                  <span class="badge bg-danger">Đã khóa</span>
                <?php else: ?>
                  <span class="badge bg-success">Hoạt động</span>
                <?php endif; ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Stats Card -->
    <div class="col-12 col-lg-6 mb-4">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body" style="color: #cbd5e1;">
          <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
            <i class="bi bi-graph-up"></i> Thống kê
          </h5>

          <div class="mb-4">
            <label style="color: #94a3b8; font-size: 0.9rem;">Tổng số đơn hàng</label>
            <p style="color: #667eea; font-size: 1.8rem; font-weight: 700; margin: 0;">
              <?= intval($stats['total_orders'] ?? 0) ?>
            </p>
          </div>

          <div class="mb-4">
            <label style="color: #94a3b8; font-size: 0.9rem;">Tổng tiền chi tiêu</label>
            <p style="color: #22c55e; font-size: 1.8rem; font-weight: 700; margin: 0;">
              <?= number_format((float)($stats['total_spent'] ?? 0), 0, ',', '.') ?> VNĐ
            </p>
          </div>

          <div style="background: rgba(102, 126, 234, 0.1); padding: 1rem; border-radius: 8px; border-left: 3px solid #667eea;">
            <p style="color: #94a3b8; font-size: 0.9rem; margin: 0;">
              <i class="bi bi-info-circle"></i> Khách hàng này đã thực hiện <strong><?= intval($stats['total_orders'] ?? 0) ?></strong> giao dịch với tổng giá trị <strong><?= number_format((float)($stats['total_spent'] ?? 0), 0, ',', '.') ?> VNĐ</strong>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Orders -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body" style="color: #cbd5e1;">
          <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
            <i class="bi bi-receipt"></i> Đơn hàng gần đây (10 đơn cuối cùng)
          </h5>

          <?php if (empty($orders)): ?>
            <p style="text-align: center; color: #94a3b8;">Chưa có đơn hàng</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover mb-0" style="color: #cbd5e1;">
                <thead style="background: rgba(102,126,234,0.1); border-bottom: 2px solid rgba(148,163,184,0.2);">
                  <tr>
                    <th style="color: #f8fafc;">Mã đơn hàng</th>
                    <th style="color: #f8fafc;">Ngày đặt</th>
                    <th style="color: #f8fafc;">Tổng tiền</th>
                    <th style="color: #f8fafc; text-align: center;">Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order): ?>
                    <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                      <td style="vertical-align: middle;">
                        <a href="<?= APP_URL ?>/Admin/orderDetail/<?= $order['id'] ?>" style="color: #667eea; text-decoration: none;">
                          #<?= htmlspecialchars($order['id']) ?>
                        </a>
                      </td>
                      <td style="vertical-align: middle;">
                        <?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A' ?>
                      </td>
                      <td style="vertical-align: middle;">
                        <strong style="color: #22c55e;">
                          <?= number_format((float)($order['total_amount'] ?? 0), 0, ',', '.') ?> VNĐ
                        </strong>
                      </td>
                      <td style="vertical-align: middle; text-align: center;">
                        <?php 
                          $status = $order['delivery_status'] ?? '';
                          $badgeClass = 'bg-secondary';
                          $statusText = 'N/A';
                          
                          if ($status === 'pending') {
                            $badgeClass = 'bg-warning';
                            $statusText = 'Chờ xác nhận';
                          } elseif ($status === 'confirmed') {
                            $badgeClass = 'bg-info';
                            $statusText = 'Đã xác nhận';
                          } elseif ($status === 'shipping') {
                            $badgeClass = 'bg-primary';
                            $statusText = 'Đang giao';
                          } elseif ($status === 'da_nhan_hang') {
                            $badgeClass = 'bg-success';
                            $statusText = 'Đã nhận';
                          } elseif ($status === 'cancelled') {
                            $badgeClass = 'bg-danger';
                            $statusText = 'Đã hủy';
                          }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
