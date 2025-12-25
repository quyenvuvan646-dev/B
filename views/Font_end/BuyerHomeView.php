<?php
$user = $_SESSION['user'] ?? null;
$products = $data['productList'] ?? [];
?>
<style>
/* Custom styles for buyer product grid */
.buyer-products .card { border: 0; border-radius: 12px; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
.buyer-products .card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.buyer-products .card img { height: 200px; object-fit: cover; object-position: center center; width: 100%; display: block; }
.buyer-products .image-wrap { height: 200px; overflow: hidden; display: block; background: #f8f9fa; }
.buyer-products .card-body { display: flex; flex-direction: column; }
.buyer-products .card-title { font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.price-old { text-decoration: line-through; color: #888; margin-right: .5rem; }
.price-new { color: #d9534f; font-weight: 700; }
.product-actions { display: flex; gap: .5rem; }
.product-actions a { flex: 1; }
.buyer-hero { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; }
@media (max-width: 576px) {
    .buyer-products .card img { height: 160px; }
    .buyer-hero { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Trang Người Mua</h2>
            <p class="mb-0 small text-muted">Xin chào, <?php echo htmlspecialchars($user['email'] ?? 'Bạn'); ?>.</p>
        </div>
        <div>
            <a class="btn btn-outline-primary me-2" href="<?php echo APP_URL; ?>/Home/order">Giỏ hàng</a>
            <a class="btn btn-outline-secondary" href="<?php echo APP_URL; ?>/Home/orderHistory">Lịch sử đơn hàng</a>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">Hiện chưa có sản phẩm để hiển thị.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 buyer-products">
            <?php foreach ($products as $p): 
                $price = isset($p['giaXuat']) ? (float)$p['giaXuat'] : 0;
                $hasPromo = !empty($p['phantram']);
                $promoPrice = $hasPromo ? $price * (1 - ((float)$p['phantram'] / 100)) : $price;
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <a href="<?= APP_URL ?>/Home/detail/<?= urlencode($p['masp']) ?>" class="d-block text-center image-wrap">
                            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($p['hinhanh'] ?? 'placeholder.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($p['tensp'] ?? '') ?>">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="<?= htmlspecialchars($p['tensp'] ?? '') ?>"><?= htmlspecialchars($p['tensp'] ?? 'Không tên') ?></h5>
                            <p class="card-text mb-2">
                                <?php if ($hasPromo): ?>
                                    <span class="text-decoration-line-through text-muted me-2"><?= number_format($price,0,',','.') ?> ₫</span>
                                    <span class="fw-bold text-danger"><?= number_format($promoPrice,0,',','.') ?> ₫</span>
                                <?php else: ?>
                                    <span class="fw-bold text-danger"><?= number_format($price,0,',','.') ?> ₫</span>
                                <?php endif; ?>
                            </p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="<?= APP_URL ?>/Home/addtocard/<?= urlencode($p['masp']) ?>" class="btn btn-sm btn-primary">Thêm vào giỏ</a>
                                <a href="<?= APP_URL ?>/Home/detail/<?= urlencode($p['masp']) ?>" class="btn btn-sm btn-outline-secondary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
