<style>
    .compare-container{max-width:1200px;margin:30px auto;padding:0 16px}
    .compare-card{background:linear-gradient(135deg,#1e293b,#0f172a);border:1px solid #334155;border-radius:16px;padding:24px;color:#e2e8f0;box-shadow:0 12px 24px rgba(15,23,42,.4)}
    .compare-header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px}
    .compare-title{font-size:22px;font-weight:700;color:#fff}
    .compare-selects{display:grid;grid-template-columns:1fr 1fr auto;gap:12px}
    .compare-selects .form-control{background:#0f172a;border:1px solid #334155;color:#e2e8f0;padding:10px 12px;border-radius:8px}
    .btn-compare{background:linear-gradient(135deg,#3b82f6,#1d4ed8);border:none;color:#fff;font-weight:700;padding:12px 18px;border-radius:10px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px}
    .product-box{background:linear-gradient(135deg,#0f172a,#111827);border:1px solid #374151;border-radius:14px;padding:16px}
    .product-head{display:flex;align-items:center;gap:12px;margin-bottom:12px}
    .product-head img{width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #334155}
    .product-name{font-weight:700;color:#93c5fd}
    .stat{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px dashed #334155}
    .stat:last-child{border-bottom:none}
    .stat .label{color:#cbd5e1}
    .stat .value{color:#6ee7b7;font-weight:700}
    .center-col{display:flex;flex-direction:column;gap:8px;margin:24px 0}
    .vs{align-self:center;background:#1f2937;color:#93c5fd;border:1px solid #334155;border-radius:999px;padding:6px 12px;font-weight:800;letter-spacing:.08em}
    @media(max-width: 900px){.grid{grid-template-columns:1fr}}
</style>

<div class="compare-container">
    <div class="compare-card">
        <div class="compare-header">
            <div class="compare-title">🔍 So sánh 2 sản phẩm</div>
            <form class="compare-selects" method="GET" action="<?php echo APP_URL; ?>/Home/compare">
                <select name="product1" class="form-control" required>
                    <option value="">-- Chọn sản phẩm 1 --</option>
                    <?php foreach(($products ?? []) as $p): ?>
                        <option value="<?php echo htmlspecialchars($p['masp']); ?>" <?php echo (isset($selected1) && $selected1==$p['masp'])?'selected':''; ?>>
                            <?php echo htmlspecialchars($p['tensp']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="product2" class="form-control" required>
                    <option value="">-- Chọn sản phẩm 2 --</option>
                    <?php foreach(($products ?? []) as $p): ?>
                        <option value="<?php echo htmlspecialchars($p['masp']); ?>" <?php echo (isset($selected2) && $selected2==$p['masp'])?'selected':''; ?>>
                            <?php echo htmlspecialchars($p['tensp']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn-compare" type="submit">So sánh</button>
            </form>
        </div>

        <?php if (!empty($p1) && !empty($p2)): ?>
            <div class="grid">
                <div class="product-box">
                    <div class="product-head">
                        <img src="<?php echo APP_URL.'/public/images/'.htmlspecialchars($p1['hinhanh'] ?? ''); ?>" alt="product1">
                        <div class="product-name"><?php echo htmlspecialchars($p1['tensp']); ?></div>
                    </div>
                    <div class="stat"><div class="label">Giá gốc</div><div class="value"><?php echo number_format($p1['gia_goc']); ?>₫</div></div>
                    <div class="stat"><div class="label">Khuyến mãi</div><div class="value"><?php echo (float)$p1['phantram']; ?>%</div></div>
                    <div class="stat"><div class="label">Giá sau KM</div><div class="value"><?php echo number_format($p1['gia_khuyenmai']); ?>₫</div></div>
                    <div class="stat"><div class="label">Số lượng còn lại</div><div class="value"><?php echo (int)($p1['soluong'] ?? 0); ?></div></div>
                    <div class="stat"><div class="label">Số lượt mua</div><div class="value"><?php echo (int)$p1['sold_count']; ?></div></div>
                    <div class="stat"><div class="label">Sao trung bình</div><div class="value"><?php echo number_format($p1['avg_rating'],1); ?> ★</div></div>
                </div>

                <div class="center-col">
                    <div class="vs">VS</div>
                </div>

                <div class="product-box">
                    <div class="product-head">
                        <img src="<?php echo APP_URL.'/public/images/'.htmlspecialchars($p2['hinhanh'] ?? ''); ?>" alt="product2">
                        <div class="product-name"><?php echo htmlspecialchars($p2['tensp']); ?></div>
                    </div>
                    <div class="stat"><div class="label">Giá gốc</div><div class="value"><?php echo number_format($p2['gia_goc']); ?>₫</div></div>
                    <div class="stat"><div class="label">Khuyến mãi</div><div class="value"><?php echo (float)$p2['phantram']; ?>%</div></div>
                    <div class="stat"><div class="label">Giá sau KM</div><div class="value"><?php echo number_format($p2['gia_khuyenmai']); ?>₫</div></div>
                    <div class="stat"><div class="label">Số lượng còn lại</div><div class="value"><?php echo (int)($p2['soluong'] ?? 0); ?></div></div>
                    <div class="stat"><div class="label">Số lượt mua</div><div class="value"><?php echo (int)$p2['sold_count']; ?></div></div>
                    <div class="stat"><div class="label">Sao trung bình</div><div class="value"><?php echo number_format($p2['avg_rating'],1); ?> ★</div></div>
                </div>
            </div>
        <?php else: ?>
            <div style="color:#94a3b8;margin-top:8px">Chọn 2 sản phẩm và bấm "So sánh" để xem chi tiết.</div>
        <?php endif; ?>
    </div>
</div>
