<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col-12">
      <a href="<?= APP_URL ?>/Admin/shops" class="btn btn-sm btn-outline-light mb-3">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
      <h3 style="color: #f8fafc;">
        <i class="bi bi-shop"></i> Thông tin chi tiết shop
      </h3>
    </div>
  </div>

  <div class="row">
    <!-- Shop Info Card -->
    <div class="col-12 col-lg-6 mb-4">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body" style="color: #cbd5e1;">
          <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
            <i class="bi bi-card-text"></i> Thông tin cơ bản
          </h5>
          
          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Tên shop</label>
            <p style="color: #f8fafc; font-weight: 600;">
              <?= htmlspecialchars($shop['shop_name'] ?? 'N/A') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Email</label>
            <p style="color: #f8fafc; word-break: break-all;">
              <?= htmlspecialchars($shop['email'] ?? '') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Điện thoại</label>
            <p style="color: #f8fafc;">
              <?= htmlspecialchars($shop['phone'] ?? 'Chưa cập nhật') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Địa chỉ</label>
            <p style="color: #f8fafc;">
              <?= htmlspecialchars($shop['address'] ?? 'Chưa cập nhật') ?>
            </p>
          </div>

          <div class="mb-3">
            <label style="color: #94a3b8; font-size: 0.9rem;">Ngày tạo tài khoản</label>
            <p style="color: #f8fafc;">
              <?= !empty($shop['created_at']) ? date('d/m/Y H:i', strtotime($shop['created_at'])) : 'N/A' ?>
            </p>
          </div>

          <div>
            <label style="color: #94a3b8; font-size: 0.9rem;">Trạng thái</label>
            <p>
              <?php if ($shop['is_locked']): ?>
                <span class="badge bg-danger">Đã khóa</span>
              <?php else: ?>
                <span class="badge bg-success">Hoạt động</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Shop Stats Card -->
    <div class="col-12 col-lg-6 mb-4">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body" style="color: #cbd5e1;">
          <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
            <i class="bi bi-graph-up"></i> Thống kê
          </h5>

          <div class="row mb-3">
            <div class="col-6">
              <label style="color: #94a3b8; font-size: 0.9rem;">Số sản phẩm</label>
              <p style="color: #667eea; font-size: 1.5rem; font-weight: 700; margin: 0;">
                <?= intval($product_count ?? 0) ?>
              </p>
            </div>
            <div class="col-6">
              <label style="color: #94a3b8; font-size: 0.9rem;">Rating</label>
              <p style="color: #fbbf24; font-size: 1.5rem; font-weight: 700; margin: 0;">
                ⭐ <?= number_format($avg_rating ?? 0, 1) ?>
              </p>
              <small style="color: #94a3b8;">(<?= intval($review_count ?? 0) ?> đánh giá)</small>
            </div>
          </div>

          <div class="mb-4">
            <label style="color: #94a3b8; font-size: 0.9rem;">Tổng số đơn hàng</label>
            <p style="color: #667eea; font-size: 1.8rem; font-weight: 700; margin: 0;">
              <?= intval($stats['total_orders'] ?? 0) ?>
            </p>
          </div>

          <div class="mb-4">
            <label style="color: #94a3b8; font-size: 0.9rem;">Doanh thu (đã nhận)</label>
            <p style="color: #22c55e; font-size: 1.8rem; font-weight: 700; margin: 0;">
              <?= number_format((float)($stats['total_revenue'] ?? 0), 0, ',', '.') ?> VNĐ
            </p>
          </div>

          <div style="background: rgba(102, 126, 234, 0.1); padding: 1rem; border-radius: 8px; border-left: 3px solid #667eea;">
            <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">
              <i class="bi bi-info-circle"></i> Shop này có <strong><?= intval($product_count ?? 0) ?></strong> sản phẩm và <strong><?= intval($stats['total_orders'] ?? 0) ?></strong> đơn hàng hoàn thành
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Products -->
  <?php if (!empty($topProducts)): ?>
    <div class="row mb-4">
      <div class="col-12">
        <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
          <div class="card-body" style="color: #cbd5e1;">
            <h5 style="color: #f8fafc; margin-bottom: 1.5rem;">
              <i class="bi bi-star"></i> Top 5 sản phẩm bán chạy
            </h5>

            <div class="table-responsive">
              <table class="table table-hover mb-0" style="color: #cbd5e1;">
                <thead style="background: rgba(102,126,234,0.1); border-bottom: 2px solid rgba(148,163,184,0.2);">
                  <tr>
                    <th style="color: #f8fafc;">Mã sản phẩm</th>
                    <th style="color: #f8fafc;">Tên sản phẩm</th>
                    <th style="color: #f8fafc; text-align: center;">Số lượng bán</th>
                    <th style="color: #f8fafc; text-align: right;">Doanh thu</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($topProducts as $product): ?>
                    <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                      <td style="vertical-align: middle;">
                        <small style="color: #94a3b8;">
                          <?= htmlspecialchars($product['masp']) ?>
                        </small>
                      </td>
                      <td style="vertical-align: middle;">
                        <?= htmlspecialchars($product['tensp']) ?>
                      </td>
                      <td style="vertical-align: middle; text-align: center;">
                        <strong style="color: #667eea;">
                          <?= intval($product['sold_count']) ?>
                        </strong>
                      </td>
                      <td style="vertical-align: middle; text-align: right;">
                        <strong style="color: #22c55e;">
                          <?= number_format((float)($product['revenue'] ?? 0), 0, ',', '.') ?> VNĐ
                        </strong>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
