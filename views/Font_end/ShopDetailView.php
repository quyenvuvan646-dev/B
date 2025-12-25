<div class="container py-5">
  <!-- Shop Header -->
  <div class="row mb-5">
    <div class="col-12">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2);">
        <div class="card-body p-5">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h2 style="color: #f8fafc; margin-bottom: 1rem;">
                <i class="bi bi-shop" style="color: #667eea;"></i>
                <?= htmlspecialchars($shop['fullname'] ?? 'Shop') ?>
              </h2>
              
              <div class="mb-3" style="color: #cbd5e1;">
                <p class="mb-2">
                  <i class="bi bi-star-fill" style="color: #fbbf24;"></i>
                  <strong style="color: #fbbf24; font-size: 1.3rem;">
                    <?= number_format($shop['avg_rating'] ?? 0, 1) ?>
                  </strong>
                  <span style="color: #94a3b8;">(<?= intval($shop['review_count'] ?? 0) ?> đánh giá)</span>
                </p>
                <p class="mb-2">
                  <i class="bi bi-box-seam"></i>
                  <strong><?= intval($shop['product_count'] ?? 0) ?> sản phẩm</strong>
                </p>
              </div>
            </div>
            <div class="col-md-4 text-md-end" style="color: #cbd5e1;">
              <div class="mb-3">
                <i class="bi bi-telephone"></i>
                <strong><?= htmlspecialchars($shop['phone'] ?? 'N/A') ?></strong>
              </div>
              <div>
                <i class="bi bi-geo-alt"></i>
                <span><?= htmlspecialchars($shop['address'] ?? 'N/A') ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Products Section -->
  <div class="row mb-4">
    <div class="col-12">
      <h4 style="color: #f8fafc;">
        <i class="bi bi-list"></i> Sản phẩm của shop
      </h4>
    </div>
  </div>

  <?php if (empty($products)): ?>
    <div class="alert alert-info" style="color: #cbd5e1;">
      <i class="bi bi-info-circle"></i> Shop này chưa có sản phẩm nào.
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($products as $product): ?>
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card h-100" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2); color: #e2e8f0; cursor: pointer; transition: all 0.3s ease; position: relative;" 
               onmouseover="this.style.borderColor='#667eea'; this.style.boxShadow='0 8px 16px rgba(102, 126, 234, 0.3)';"
               onmouseout="this.style.borderColor='rgba(148,163,184,0.2)'; this.style.boxShadow='none';">
            <?php if ($product['has_promotion'] ?? false): ?>
              <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4); z-index: 10;">KM</div>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/Home/detail/<?= urlencode($product['masp']) ?>" style="text-decoration: none; color: inherit;">
              <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($product['hinhanh'] ?? 'placeholder.png') ?>" 
                   alt="<?= htmlspecialchars($product['tensp']) ?>" 
                   class="card-img-top" style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h6 class="card-title" style="color: #f8fafc; height: 2.4em; overflow: hidden;">
                  <?= htmlspecialchars(substr($product['tensp'], 0, 60)) ?>
                </h6>
                <div class="mb-2">
                  <small style="color: #94a3b8;"><?= htmlspecialchars(substr($product['mota'] ?? '', 0, 50)) ?></small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <?php 
                      $hasPromo = ($product['has_promotion'] ?? false) && (($product['discount_percent'] ?? 0) > 0);
                      if ($hasPromo) {
                        $old = (float)($product['giaXuat'] ?? 0);
                        $pct = (float)$product['discount_percent'];
                        $new = $old * (1 - $pct / 100.0);
                      }
                    ?>
                    <?php if ($hasPromo): ?>
                      <span style="color:#94a3b8; text-decoration: line-through; margin-right:8px;">
                        <?= number_format($old, 0, ',', '.') ?> VNĐ
                      </span>
                      <span style="color:#22c55e; font-weight:700;">
                        <?= number_format($new, 0, ',', '.') ?> VNĐ
                      </span>
                      <span class="ms-2" style="color:#ef4444; font-weight:700;">-<?= (int)$pct ?>%</span>
                    <?php else: ?>
                      <strong style="color: #667eea; font-size: 1.1rem;">
                        <?= number_format(intval($product['giaXuat']), 0, ',', '.') ?> VNĐ
                      </strong>
                    <?php endif; ?>
                  </div>
                  <div style="text-align: right;">
                    <?php 
                      $rating = floatval($product['avg_rating'] ?? 0);
                      if ($rating > 0):
                    ?>
                      <span style="color: #fbbf24;">
                        <i class="bi bi-star-fill"></i> <?= number_format($rating, 1) ?>
                      </span>
                    <?php else: ?>
                      <span style="color: #94a3b8; font-size: 0.9rem;">Chưa có đánh giá</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- Back Button -->
  <div class="text-center mt-5">
    <a href="<?= APP_URL ?>/Home/products" class="btn btn-outline-light">
      <i class="bi bi-arrow-left"></i> Quay lại
    </a>
  </div>
</div>
