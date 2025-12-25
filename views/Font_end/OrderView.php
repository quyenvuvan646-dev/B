<style>
    .cart-shell { background:linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border:1px solid #334155; border-radius:18px; padding:28px; box-shadow:0 20px 60px rgba(15,23,42,.5); }
    .cart-item-card { background:linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border:1px solid #3b5998; border-radius:14px; padding:20px; margin-bottom:16px; color:#f1f5f9; box-shadow:0 4px 12px rgba(59,130,246,.12); }
    .cart-item-card:hover { box-shadow:0 8px 24px rgba(59,130,246,.2); }
    .cart-meta-label { font-size:11px; text-transform:uppercase; color:#94a3b8; letter-spacing:.05em; margin-bottom:4px; font-weight:700; }
    .pill-discount { background:linear-gradient(135deg, rgba(16,185,129,.22), rgba(5,150,105,.18)); color:#6ee7b7; border:1px solid rgba(16,185,129,.4); padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; }
    .btn-ghost { border:1px solid #475569; color:#f1f5f9; background:rgba(51,65,85,.3); font-weight:600; padding:8px 18px; border-radius:8px; }
    .btn-ghost:hover { border-color:#3b82f6; color:#fff; background:rgba(59,130,246,.25); }
    .btn-cta { background:linear-gradient(135deg, #3b82f6, #8b5cf6); color:#fff; border:none; font-weight:600; padding:10px 20px; border-radius:8px; }
    .btn-cta:hover { opacity:.95; color:#fff; box-shadow:0 8px 20px rgba(59,130,246,.35); }
    .cart-empty { background:rgba(59,130,246,.1); border:1px dashed #3b82f6; color:#f1f5f9; }
    .cart-summary { background:linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border:2px solid #3b82f6; border-radius:14px; padding:24px; margin-top:24px; color:#f1f5f9; box-shadow:0 8px 24px rgba(59,130,246,.15); }
    .summary-title { font-size:14px; text-transform:uppercase; color:#93c5fd; letter-spacing:.05em; margin-bottom:16px; font-weight:700; }
    .summary-row { display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid #334155; font-size:14px; }
    .summary-row:last-child { border-bottom:none; }
    .summary-total { display:flex; justify-content:space-between; padding:16px 0; margin-top:8px; border-top:2px solid #3b82f6; font-size:18px; font-weight:700; color:#6ee7b7; }
    .empty-summary { color:#94a3b8; text-align:center; padding:20px; }
</style>

<?php if (!empty($data['success'])): ?>
    <div class="alert alert-success text-center mt-3">
        <?= htmlspecialchars($data['success']) ?>
    </div>
<?php endif; ?>

<form action="<?= APP_URL ?>/Home/update" method="post">
    <div class="container my-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <p class="text-uppercase text-muted mb-1" style="letter-spacing:.08em;">Giỏ hàng</p>
                <h2 class="fw-bold text-white mb-0">🛍️ Giỏ hàng của bạn</h2>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <a href="<?= APP_URL ?>/Home/products" class="btn btn-ghost">← Tiếp tục mua sắm</a>
                <button type="submit" class="btn btn-ghost">🔄 Cập nhật</button>
            </div>
        </div>

        <?php if (empty($data["listProductOrder"])): ?>
            <div class="cart-empty text-center p-4 rounded-4">
                Giỏ hàng của bạn đang trống 😢
                <a href="<?= APP_URL ?>/Home/products" class="alert-link fw-bold">Mua sắm ngay!</a>
            </div>
        <?php else: ?>
            <div class="cart-shell">
                <?php
                $tongtien = 0;
                foreach ($data["listProductOrder"] as $v):
                    $phantram = 0;
                    if (!empty($v["phantram"])) $phantram = (float)$v["phantram"];
                    elseif (!empty($v["khuyenmai"])) $phantram = (float)$v["khuyenmai"];

                    $giaGoc = (float)$v["giaxuat"];
                    $giaSauKM = ($phantram > 0) ? $giaGoc * (1 - $phantram / 100) : $giaGoc;
                    $thanhtien = $giaSauKM * (int)$v["qty"];
                    if (!empty($v["note_cart"]) && $v["note_cart"] == 1) {
                        $tongtien += $thanhtien;
                    }
                    $checked = !empty($v["note_cart"]) && $v["note_cart"] == 1 ? 'checked' : '';
                ?>
                    <div class="cart-item-card" data-price="<?= $thanhtien ?>">
                        <div class="d-flex align-items-start gap-3">
                            <input type="checkbox" class="form-check-input item-checkbox mt-2"
                                name="note_cart[<?= $v['masp'] ?>]"
                                value="1"
                                <?= $checked ?>
                                onchange="updateCartDisplay()"
                                style="width:20px; height:20px; cursor:pointer;">
                            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>"
                                alt="<?= htmlspecialchars($v['tensp']) ?>"
                                class="rounded" style="width: 100px; height: 100px; object-fit: contain; background:#1e293b; padding:8px; border:1px solid #334155;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="text-white fw-bold mb-1"><?= htmlspecialchars($v["tensp"]) ?></h6>
                                        <div class="text-muted small mb-2">Mã: <?= htmlspecialchars($v["masp"]) ?></div>
                                        <?php if ($phantram > 0): ?><span class="pill-discount">-<?= $phantram ?>%</span><?php endif; ?>
                                    </div>
                                    <a href="<?= APP_URL ?>/Home/delete/<?= $v['masp'] ?>"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');"
                                        style="padding:4px 12px;">🗑️ Xóa</a>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <div class="cart-meta-label">GIÁ</div>
                                        <div class="text-white fw-semibold"><?= number_format($giaSauKM, 0, ',', '.') ?> ₫</div>
                                        <?php if ($phantram > 0): ?><div class="text-muted small"><s><?= number_format($giaGoc, 0, ',', '.') ?> ₫</s></div><?php endif; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="cart-meta-label">SỐ LƯỢNG</div>
                                        <input type="number" name="qty[<?= $v['masp'] ?>]"
                                            value="<?= (int)$v["qty"] ?>" min="1"
                                            class="form-control form-control-sm"
                                            style="width: 90px; background:#1e293b; color:#f1f5f9; border:1px solid #334155;">
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <div class="cart-meta-label">THÀNH TIỀN</div>
                                        <div class="fw-bold item-total" style="color:#6ee7b7; font-size:18px;">
                                            <?= number_format($thanhtien, 0, ',', '.') ?> ₫
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="submit" class="btn btn-ghost">🔄 Cập nhật giỏ</button>
                    <a href="<?= APP_URL ?>/Home/checkout" class="btn btn-cta">🛒 Đặt hàng</a>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="summary-title">📊 Tóm Tắt Đơn Hàng</div>
                    <div id="summary-content">
                        <div class="empty-summary">Chọn sản phẩm để xem tóm tắt</div>
                    </div>
                </div>
            </div>

            <script>
                function updateCartDisplay() {
                    const checkboxes = document.querySelectorAll('.item-checkbox');
                    let total = 0;
                    let selectedCount = 0;
                    
                    checkboxes.forEach(chk => {
                        if (chk.checked) {
                            const card = chk.closest('.cart-item-card');
                            const price = parseFloat(card.dataset.price);
                            const totalText = card.querySelector('.item-total').textContent;
                            const totalPrice = parseFloat(totalText.replace(/[^0-9]/g, ''));
                            
                            total += totalPrice;
                            selectedCount++;
                        }
                    });
                    
                    // Update summary
                    const summaryContent = document.getElementById('summary-content');
                    if (selectedCount === 0) {
                        summaryContent.innerHTML = '<div class="empty-summary">Chọn sản phẩm để xem tóm tắt</div>';
                    } else {
                        summaryContent.innerHTML = `
                            <div class="summary-row">
                                <span>Số lượng sản phẩm:</span>
                                <strong>${selectedCount}</strong>
                            </div>
                            <div class="summary-total">
                                <span>💰 Tổng Tiền:</span>
                                <span style="color:#10b981;">${Number(total).toLocaleString('vi-VN')} ₫</span>
                            </div>
                        `;
                    }
                }
                
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.item-checkbox').forEach(chk => chk.addEventListener('change', updateCartDisplay));
                    updateCartDisplay();
                });
            </script>
        <?php endif; ?>
    </div>
</form>
