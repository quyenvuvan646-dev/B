<div class="container py-5">
  <div class="row mb-4">
    <div class="col-12">
      <h3 style="color: #f8fafc;">Kết quả tìm kiếm shop: "<strong><?= htmlspecialchars($data['searchTerm'] ?? '') ?></strong>"</h3>
      <p style="color: #cbd5e1;">Tìm được <?= count($data['shopsData'] ?? []) ?> shop</p>
    </div>
  </div>

  <?php if (empty($data['shopsData'])): ?>
    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Không tìm được shop nào phù hợp với từ khóa của bạn.
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($data['shopsData'] as $shop): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2); color: #e2e8f0;">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-gradient p-3 rounded-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                  <i class="bi bi-shop text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div class="ms-3">
                  <h5 class="card-title mb-0" style="color: #f8fafc;">
                    <?= htmlspecialchars($shop['shop_name'] ?? 'Unknown') ?>
                  </h5>
                  <small style="color: #94a3b8;"><?= htmlspecialchars($shop['email'] ?? '') ?></small>
                </div>
              </div>

              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                  <span style="color: #cbd5e1;">
                    <i class="bi bi-box-seam"></i> <?= intval($shop['product_count'] ?? 0) ?> sản phẩm
                  </span>
                  <span style="color: #fbbf24;">
                    <i class="bi bi-star-fill"></i> <?= number_format($shop['avg_rating'] ?? 0, 1) ?>
                  </span>
                </div>
              </div>

              <?php if (!empty($shop['address'])): ?>
                <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 1rem;">
                  <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($shop['address']) ?>
                </p>
              <?php endif; ?>

              <a href="<?= APP_URL ?>/Home/shopDetail/<?= urlencode($shop['email']) ?>" class="btn btn-primary w-100">
                <i class="bi bi-info-circle"></i> Xem thông tin shop
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="text-center mt-5">
    <a href="<?= APP_URL ?>/Home/products" class="btn btn-outline-light">
      <i class="bi bi-arrow-left"></i> Quay lại
    </a>
  </div>
</div>

<style>
  .bg-gradient {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }
</style>
