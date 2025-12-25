<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col-12">
      <h3 style="color: #f8fafc;">
        <i class="bi bi-shop"></i> Danh sách Shop (Distributor)
      </h3>
      <p style="color: #cbd5e1;">Quản lý tất cả các shop bán hàng trên nền tảng</p>
    </div>
  </div>

  <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2); color: #e2e8f0;">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0" style="color: #cbd5e1;">
          <thead style="background: rgba(102,126,234,0.1); border-bottom: 2px solid rgba(148,163,184,0.2);">
            <tr>
              <th style="color: #f8fafc;">Shop</th>
              <th style="color: #f8fafc;">Email</th>
              <th style="color: #f8fafc;" class="text-center">Sản phẩm</th>
              <th style="color: #f8fafc;" class="text-center">Rating</th>
              <th style="color: #f8fafc;" class="text-center">Đơn hàng</th>
              <th style="color: #f8fafc;" class="text-center">Doanh thu</th>
              <th style="color: #f8fafc;" class="text-center">Trạng thái</th>
              <th style="color: #f8fafc;" class="text-center">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data['shops'])): ?>
              <tr>
                <td colspan="8" class="text-center py-4">
                  <i class="bi bi-inbox" style="font-size: 2rem; color: #94a3b8;"></i>
                  <p style="color: #94a3b8; margin-top: 10px;">Không có shop nào</p>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($data['shops'] as $shop): ?>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                  <td style="vertical-align: middle;">
                    <div>
                      <strong><?= htmlspecialchars($shop['shop_name'] ?? 'N/A') ?></strong>
                      <?php if (!empty($shop['address'])): ?>
                        <br>
                        <small style="color: #94a3b8;"><?= htmlspecialchars(substr($shop['address'], 0, 50)) ?></small>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td style="vertical-align: middle;">
                    <small><?= htmlspecialchars($shop['email'] ?? '') ?></small>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <span class="badge bg-primary"><?= intval($shop['product_count'] ?? 0) ?></span>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <?php 
                      $rating = floatval($shop['avg_rating'] ?? 0);
                      $color = $rating >= 4 ? '#10b981' : ($rating >= 3 ? '#f59e0b' : '#ef4444');
                    ?>
                    <span style="color: <?= $color ?>; font-weight: bold;">
                      <i class="bi bi-star-fill"></i> <?= number_format($rating, 1) ?>
                    </span>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <span class="badge bg-info"><?= intval($shop['order_count'] ?? 0) ?></span>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <strong style="color: #667eea;">
                      <?= number_format(intval($shop['total_revenue'] ?? 0), 0, ',', '.') ?> VNĐ
                    </strong>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <?php if ($shop['is_locked'] == 1): ?>
                      <span class="badge bg-danger">Khóa</span>
                    <?php else: ?>
                      <span class="badge bg-success">Hoạt động</span>
                    <?php endif; ?>
                  </td>
                  <td style="vertical-align: middle; text-align: center;">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="<?= APP_URL ?>/Admin/shopDetail/<?= urlencode($shop['email']) ?>" 
                         class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                      </a>
                      <?php if ($shop['is_locked'] == 1): ?>
                        <a href="<?= APP_URL ?>/Admin/lockUser/<?= urlencode($shop['email']) ?>/0" 
                           class="btn btn-sm btn-outline-success" title="Mở khóa">
                          <i class="bi bi-unlock"></i>
                        </a>
                      <?php else: ?>
                        <a href="<?= APP_URL ?>/Admin/lockUser/<?= urlencode($shop['email']) ?>/1" 
                           class="btn btn-sm btn-outline-danger" title="Khóa">
                          <i class="bi bi-lock"></i>
                        </a>
                      <?php endif; ?>
                      <button type="button" class="btn btn-sm btn-outline-secondary" 
                              onclick="deleteShop('<?= urlencode($shop['email']) ?>')" 
                              title="Xóa">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function deleteShop(email) {
  if (confirm('Bạn có chắc chắn muốn xóa shop này? Hành động này không thể hoàn tác.')) {
    window.location.href = '<?= APP_URL ?>/Admin/deleteUser/' + email;
  }
}
</script>

<style>
.btn-group-sm .btn {
  padding: 4px 8px;
  font-size: 0.85rem;
}
</style>
