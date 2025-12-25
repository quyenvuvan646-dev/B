<style>
    .order-hist-shell { background:linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border:1px solid #334155; border-radius:18px; padding:28px; box-shadow:0 20px 60px rgba(15,23,42,.5); }
    .order-card { background:linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border:1px solid #3b5998; border-radius:14px; padding:20px; margin-bottom:16px; color:#f1f5f9; box-shadow:0 4px 12px rgba(59,130,246,.12); }
    .order-card:hover { box-shadow:0 8px 24px rgba(59,130,246,.2); }
    .order-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #475569; }
    .order-code { font-size:18px; font-weight:700; color:#93c5fd; }
    .order-meta { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:14px; }
    .meta-item { display:flex; flex-direction:column; }
    .meta-label { font-size:11px; text-transform:uppercase; color:#94a3b8; letter-spacing:.05em; }
    .meta-value { font-size:14px; font-weight:600; color:#f1f5f9; }
    .order-status-badge { padding:6px 12px; border-radius:999px; font-size:13px; font-weight:600; }
    .badge-success { background:linear-gradient(135deg, rgba(16,185,129,.25), rgba(5,150,105,.2)); color:#6ee7b7; border:1px solid rgba(16,185,129,.4); }
    .badge-warning { background:linear-gradient(135deg, rgba(251,191,36,.25), rgba(245,158,11,.2)); color:#fcd34d; border:1px solid rgba(251,191,36,.4); }
    .badge-danger { background:linear-gradient(135deg, rgba(239,68,68,.25), rgba(220,38,38,.2)); color:#fca5a5; border:1px solid rgba(239,68,68,.4); }
    .badge-info { background:linear-gradient(135deg, rgba(59,130,246,.25), rgba(37,99,235,.2)); color:#93c5fd; border:1px solid rgba(59,130,246,.4); }
    .badge-secondary { background:linear-gradient(135deg, rgba(100,116,139,.25), rgba(71,85,105,.2)); color:#cbd5e1; border:1px solid rgba(100,116,139,.4); }
    .btn-hist-detail { background:linear-gradient(135deg, #3b82f6, #8b5cf6); color:#fff; border:none; font-weight:600; padding:8px 18px; border-radius:8px; }
    .btn-hist-detail:hover { opacity:.95; color:#fff; box-shadow:0 6px 18px rgba(59,130,246,.3); }
    .btn-hist-contract { background:linear-gradient(135deg, #10b981, #059669); color:#fff; border:none; font-weight:600; padding:8px 18px; border-radius:8px; }
    .btn-hist-contract:hover { opacity:.95; color:#fff; box-shadow:0 6px 18px rgba(16,185,129,.3); }
    .btn-hist-rebuy { border:1px solid #475569; color:#f1f5f9; background:rgba(51,65,85,.3); font-weight:600; padding:8px 18px; border-radius:8px; }
    .btn-hist-rebuy:hover { border-color:#3b82f6; color:#fff; background:rgba(59,130,246,.25); }
</style>

<div class="container my-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <p class="text-uppercase text-muted mb-1" style="letter-spacing:.08em;">Lịch sử mua hàng</p>
            <h2 class="fw-bold text-white mb-0">📦 Đơn hàng của bạn</h2>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="<?= APP_URL ?>/Home/products" class="btn btn-hist-rebuy">← Tiếp tục mua sắm</a>
        </div>
    </div>

    <div class="order-hist-shell">
        <?php if (!empty($data['orders'])): foreach ($data['orders'] as $order): ?>
            <?php
                $payLabel = '---';
                $transaction = $order['transaction_info'] ?? '';
                $isPaid = !empty($order['is_paid']) && $order['is_paid'] == 1;
                $deliveryStatus = $order['delivery_status'] ?? '';

                if ($transaction === 'thanhtoanvnpay' || $transaction === 'dathanhtoan') {
                    $payLabel = 'Thanh toán online';
                } elseif ($transaction === 'chothanhtoan') {
                    $payLabel = 'COD (Thanh toán khi nhận hàng)';
                } elseif ($transaction === 'huydon') {
                    $payLabel = 'Thanh toán online';
                }

                $badgeClass = 'secondary';
                $statusText = '';
                if ($deliveryStatus === 'da_nhan_hang') {
                    $badgeClass = 'success';
                    $statusText = '✓ Đã nhận hàng — Đã thanh toán';
                } elseif ($deliveryStatus === 'da_tra_hang') {
                    $badgeClass = 'warning';
                    $statusText = '⚠ Chưa thanh toán — Trả hàng';
                } elseif ($deliveryStatus === 'da_huy') {
                    $badgeClass = 'danger';
                    $statusText = '✕ Đã hủy đơn';
                } elseif ($transaction === 'huydon') {
                    $badgeClass = 'danger';
                    $statusText = '✕ Đơn hàng không thành công';
                } elseif ($isPaid || $transaction === 'dathanhtoan') {
                    $badgeClass = 'success';
                    $statusText = '✓ Đã thanh toán';
                } elseif ($transaction === 'thanhtoanvnpay') {
                    $badgeClass = 'warning';
                    $statusText = '⏳ Chờ thanh toán';
                } elseif ($transaction === 'chothanhtoan') {
                    $badgeClass = 'info';
                    $statusText = '💵 Thanh toán khi nhận hàng';
                } else {
                    $badgeClass = 'secondary';
                    $statusText = '—';
                }
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-code"><?= htmlspecialchars($order['order_code']) ?></div>
                    <span class="order-status-badge badge-<?= $badgeClass ?>"><?= htmlspecialchars($statusText) ?></span>
                </div>
                <div class="order-meta">
                    <div class="meta-item">
                        <span class="meta-label">Ngày đặt</span>
                        <span class="meta-value"><?= htmlspecialchars($order['created_at']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Tổng tiền</span>
                        <span class="meta-value" style="color:#6ee7b7;"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Người nhận</span>
                        <span class="meta-value"><?= htmlspecialchars($order['receiver']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Số điện thoại</span>
                        <span class="meta-value"><?= htmlspecialchars($order['phone']) ?></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="meta-label">Địa chỉ giao hàng</div>
                    <div class="meta-value"><?= htmlspecialchars($order['address']) ?></div>
                </div>
                <div class="mb-3">
                    <div class="meta-label">Phương thức thanh toán</div>
                    <div class="meta-value"><?= htmlspecialchars($payLabel) ?></div>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= APP_URL ?>/Home/orderDetail/<?= $order['id'] ?>" class="btn btn-hist-detail">Xem chi tiết</a>
                    <a href="<?= APP_URL ?>/Home/orderContract/<?= $order['id'] ?>" class="btn btn-hist-contract" title="Xem hợp đồng/hóa đơn">📄 Hợp Đồng</a>
                    <?php if ($transaction === 'huydon'): ?>
                        <a href="<?= APP_URL ?>/Home/products" class="btn btn-hist-rebuy">Mua lại</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="text-center p-4" style="color:#f1f5f9;">
                Bạn chưa có đơn hàng nào. <a href="<?= APP_URL ?>/Home/products" class="fw-bold" style="color:#93c5fd;">Mua sắm ngay!</a>
            </div>
        <?php endif; ?>
    </div>
</div>
