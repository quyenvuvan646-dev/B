<style>
.order-detail-container {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
    padding: 3rem 0;
}
.page-title {
    color: #f1f5f9;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
}
.order-info-card {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}
.order-header {
    border-bottom: 1px solid #334155;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}
.order-code {
    color: #3b5998;
    font-size: 1.25rem;
    font-weight: 700;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}
.meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.meta-label {
    color: #94a3b8;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}
.meta-value {
    color: #f1f5f9;
    font-size: 1rem;
    font-weight: 500;
}
.delivery-photo {
    margin-top: 1rem;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #334155;
    max-width: 300px;
}
.delivery-photo img {
    width: 100%;
    height: auto;
    display: block;
}
.badge-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}
.badge-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}
.badge-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}
.badge-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}
.badge-secondary {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}
.section-title {
    color: #f1f5f9;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.product-item-card {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}
.product-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(59, 89, 152, 0.2);
}
.product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #334155;
}
.product-info {
    flex: 1;
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
    gap: 1rem;
    align-items: center;
}
.product-name {
    color: #f1f5f9;
    font-weight: 600;
    font-size: 1rem;
}
.product-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.product-meta-label {
    color: #94a3b8;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.product-meta-value {
    color: #f1f5f9;
    font-weight: 600;
    font-size: 0.95rem;
}
.total-card {
    background: linear-gradient(135deg, #3b5998 0%, #1e3a5f 100%);
    border: 2px solid #3b5998;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.total-label {
    color: #f1f5f9;
    font-size: 1.25rem;
    font-weight: 700;
    text-transform: uppercase;
}
.total-value {
    color: #60a5fa;
    font-size: 1.5rem;
    font-weight: 700;
}
.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}
.btn-back {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}
.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(100, 116, 139, 0.3);
    color: white;
}
.update-section {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
}
.update-title {
    color: #f1f5f9;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.form-label {
    color: #94a3b8;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.form-select, .form-control {
    background: #0f172a;
    border: 1px solid #334155;
    color: #f1f5f9;
    border-radius: 8px;
    padding: 0.75rem;
}
.form-select:focus, .form-control:focus {
    background: #1e293b;
    border-color: #3b5998;
    color: #f1f5f9;
    box-shadow: 0 0 0 3px rgba(59, 89, 152, 0.1);
}
.btn-primary {
    background: linear-gradient(135deg, #3b5998 0%, #1e3a5f 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(59, 89, 152, 0.3);
}
.btn-cancel {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}
.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
    color: white;
}
.cancel-section {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
}
.cancel-title {
    color: #f1f5f9;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.radio-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.radio-option {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
}
.radio-option:hover {
    border-color: #3b5998;
    background: #1e293b;
}
.radio-option input[type="radio"] {
    width: 20px;
    height: 20px;
    accent-color: #3b5998;
}
.radio-option label {
    color: #f1f5f9;
    cursor: pointer;
    margin: 0;
    flex: 1;
}
@media (max-width: 768px) {
    .product-info {
        grid-template-columns: 1fr;
    }
    .product-item-card {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="order-detail-container">
    <div class="container">
        <h1 class="page-title">Chi tiết đơn hàng</h1>
        
        <?php if (!empty($data['order'])): 
            $order = $data['order'];
            $orderItems = $data['orderItems'];
        ?>
        
        <!-- Order Information Card -->
        <div class="order-info-card">
            <div class="order-header">
                <div class="order-code">MÃ ĐơN HÀNG: <?= htmlspecialchars($order['order_code']) ?></div>
            </div>
            
            <div class="info-grid">
                <div class="meta-item">
                    <div class="meta-label">NGÀY ĐẶT</div>
                    <div class="meta-value"><?= htmlspecialchars($order['created_at']) ?></div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-label">TRẠNG THÁI THANH TOÁN</div>
                    <div class="meta-value">
                        <?php
                            $transaction = $order['transaction_info'] ?? '';
                            $isPaid = !empty($order['is_paid']) && $order['is_paid'] == 1;
                            $deliveryStatus = $order['delivery_status'] ?? '';
                            
                            if ($deliveryStatus === 'da_nhan_hang') {
                                echo '<span class="badge-success">Đã nhận hàng — Đã thanh toán</span>';
                            } elseif ($deliveryStatus === 'da_tra_hang') {
                                echo '<span class="badge-warning">Chưa thanh toán — Trả hàng</span>';
                            } elseif ($transaction === 'huydon') {
                                echo '<span class="badge-danger">Đơn hàng không mua thành công</span>';
                            } elseif ($isPaid || $transaction === 'dathanhtoan') {
                                echo '<span class="badge-success">Đã thanh toán</span>';
                            } elseif ($transaction === 'thanhtoanvnpay') {
                                echo '<span class="badge-warning">Chờ thanh toán</span>';
                            } elseif ($transaction === 'chothanhtoan') {
                                echo '<span class="badge-info">Thanh toán khi nhận hàng</span>';
                            } else {
                                echo '<span class="badge-secondary">Không xác định</span>';
                            }
                        ?>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-label">TRẠNG THÁI GIAO HÀNG</div>
                    <div class="meta-value">
                        <?php
                            $dstatus = $order['delivery_status'] ?? null;
                            if ($dstatus == 'da_nhan_hang') echo '<span class="badge-success">Đã nhận hàng</span>';
                            elseif ($dstatus == 'dang_chuan_bi') echo '<span class="badge-info">Đang chuẩn bị hàng</span>';
                            elseif ($dstatus == 'da_giao_dvvc') echo '<span class="badge-info">Đã giao cho đơn vị vận chuyển</span>';
                            elseif ($dstatus == 'da_tra_hang') echo '<span class="badge-warning">Trả hàng</span>';
                            elseif ($dstatus == 'da_huy') echo '<span class="badge-danger">Đã hủy</span>';
                            else echo '<span class="badge-secondary">Chưa xác nhận</span>';
                        ?>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-label">NGƯỜI NHẬN</div>
                    <div class="meta-value"><?= htmlspecialchars($order['receiver']) ?></div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-label">ĐỊA CHỈ</div>
                    <div class="meta-value"><?= htmlspecialchars($order['address']) ?></div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-label">SỐ ĐIỆN THOẠI</div>
                    <div class="meta-value"><?= htmlspecialchars($order['phone']) ?></div>
                </div>
            </div>
            
            <?php if (!empty($order['cancel_reason'])): ?>
                <div class="meta-item" style="margin-top: 1.5rem;">
                    <div class="meta-label">LÝ DO HỦY ĐƠN</div>
                    <div class="meta-value" style="color: #fca5a5;">
                        <?= htmlspecialchars($order['cancel_reason']) ?>
                    </div>
                    <?php if (!empty($order['cancel_date'])): ?>
                        <div class="meta-label" style="margin-top: 0.5rem;">THỜI GIAN HỦY</div>
                        <div class="meta-value" style="font-size: 0.875rem; color: #cbd5e1;">
                            <?= htmlspecialchars($order['cancel_date']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($order['delivery_photo'])): ?>
                <div class="meta-item" style="margin-top: 1.5rem;">
                    <div class="meta-label">ẢNH GIAO HÀNG</div>
                    <div class="delivery-photo">
                        <img src="<?= APP_URL ?>/public/<?= htmlspecialchars($order['delivery_photo']) ?>" alt="Ảnh giao hàng" />
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Products List -->
        <h2 class="section-title">Danh sách sản phẩm</h2>
        <?php foreach ($orderItems as $item): ?>
            <div class="product-item-card">
                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="product-image">
                <div class="product-info">
                    <div class="product-name"><?= htmlspecialchars($item['product_name']) ?></div>
                    
                    <div class="product-meta">
                        <div class="product-meta-label">SỐ LƯỢNG</div>
                        <div class="product-meta-value"><?= (int)$item['quantity'] ?></div>
                    </div>
                    
                    <div class="product-meta">
                        <div class="product-meta-label">GIÁ GỐC</div>
                        <div class="product-meta-value"><?= number_format($item['price'], 0, ',', '.') ?> ₫</div>
                    </div>
                    
                    <div class="product-meta">
                        <div class="product-meta-label">GIÁ KHUYẾN MÃI</div>
                        <div class="product-meta-value"><?= number_format($item['sale_price'], 0, ',', '.') ?> ₫</div>
                    </div>
                    
                    <div class="product-meta">
                        <div class="product-meta-label">THÀNH TIỀN</div>
                        <div class="product-meta-value"><?= number_format($item['total'], 0, ',', '.') ?> ₫</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Order Summary Breakdown -->
        <div class="total-card">
            <?php
            // Tính tổng hàng từ order_details
            $totalProductPrice = 0;
            foreach ($orderItems as $item) {
                $totalProductPrice += $item['total'];
            }
            
            $shippingFee = isset($order['shipping_fee']) ? (float)$order['shipping_fee'] : 0;
            $discount = $totalProductPrice + $shippingFee - (float)$order['total_amount'];
            ?>
            
            <div style="border-bottom: 1px solid #334155; padding-bottom: 1rem; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #94a3b8;">Tổng tiền hàng:</span>
                    <span style="color: #f1f5f9;"><?= number_format($totalProductPrice, 0, ',', '.') ?> ₫</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #94a3b8;">Phí vận chuyển:</span>
                    <span style="color: #f1f5f9;"><?= number_format($shippingFee, 0, ',', '.') ?> ₫</span>
                </div>
                <?php if ($discount > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #94a3b8;">Giảm giá (Voucher):</span>
                    <span style="color: #10b981;">-<?= number_format($discount, 0, ',', '.') ?> ₫</span>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="total-label">Tổng thanh toán</div>
                <div class="total-value"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <?php
            // Nếu là admin thì quay về danh sách đơn admin, còn lại về lịch sử đơn hàng của người dùng
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            require_once __DIR__ . '/../../app/Auth.php';
            $backUrl = \Auth::hasRole('admin') ? APP_URL . '/Admin/orders' : APP_URL . '/Home/orderHistory';
            ?>
            <a href="<?= $backUrl ?>" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại lịch sử đơn hàng
            </a>
            
            <?php
            // Show cancel button for regular users (not admin/shipper) if order can be cancelled
            if (!Auth::hasRole('admin') && !Auth::hasRole('shipper')) {
                $canCancel = in_array($order['delivery_status'] ?? null, ['chua_xac_nhan', 'dang_chuan_bi', 'da_giao_dvvc', null]);
                if ($canCancel): ?>
                    <button type="button" class="btn-cancel" onclick="toggleCancelForm()">
                        <i class="bi bi-x-circle"></i> Hủy đơn hàng
                    </button>
                <?php endif;
            }
            ?>
        </div>

        <?php
        // Cancel order form for regular users
        if (!Auth::hasRole('admin') && !Auth::hasRole('shipper')) {
            $canCancel = in_array($order['delivery_status'] ?? null, ['chua_xac_nhan', 'dang_chuan_bi', 'da_giao_dvvc', null]);
            if ($canCancel): ?>
                <div class="cancel-section" id="cancelOrderForm" style="display: none;">
                    <h3 class="cancel-title">
                        <i class="bi bi-exclamation-triangle"></i> Xác nhận hủy đơn hàng
                    </h3>
                    <form action="<?= APP_URL ?>/Home/userCancelOrder" method="POST" onsubmit="return confirmCancel()">
                        <input type="hidden" name="order_id" value="<?= intval($order['id']) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Lý do hủy đơn</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reason1" value="Thay đổi địa chỉ giao hàng" required>
                                    <label for="reason1">Thay đổi địa chỉ giao hàng</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reason2" value="Sản phẩm không phù hợp" required>
                                    <label for="reason2">Sản phẩm không phù hợp</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reason3" value="Đã có sản phẩm khác thay thế" required>
                                    <label for="reason3">Đã có sản phẩm khác thay thế</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reason4" value="Đổi ý không muốn mua nữa" required>
                                    <label for="reason4">Đổi ý không muốn mua nữa</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reason5" value="Thời gian giao hàng quá lâu" required>
                                    <label for="reason5">Thời gian giao hàng quá lâu</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="cancel_reason" id="reasonOther" value="" required>
                                    <label for="reasonOther">Lý do khác (vui lòng ghi rõ bên dưới)</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="otherReasonDiv" style="display: none;">
                            <label class="form-label">Ghi rõ lý do khác</label>
                            <textarea class="form-control" id="otherReasonText" rows="3" placeholder="Vui lòng nhập lý do hủy đơn của bạn..."></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check-circle"></i> Xác nhận hủy đơn
                            </button>
                            <button type="button" class="btn-back" onclick="toggleCancelForm()">
                                <i class="bi bi-x"></i> Đóng
                            </button>
                        </div>
                    </form>
                </div>
                
                <script>
                function toggleCancelForm() {
                    const form = document.getElementById('cancelOrderForm');
                    form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    if (form.style.display === 'block') {
                        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                function confirmCancel() {
                    const reasonOther = document.getElementById('reasonOther');
                    if (reasonOther.checked) {
                        const otherText = document.getElementById('otherReasonText').value.trim();
                        if (!otherText) {
                            alert('Vui lòng nhập lý do hủy đơn!');
                            return false;
                        }
                        reasonOther.value = otherText;
                    }
                    return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?\n\nSau khi hủy, sản phẩm sẽ được hoàn lại kho và bạn có thể đặt hàng lại sau.');
                }
                
                // Show/hide other reason textarea
                document.querySelectorAll('input[name="cancel_reason"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const otherDiv = document.getElementById('otherReasonDiv');
                        if (this.id === 'reasonOther') {
                            otherDiv.style.display = 'block';
                        } else {
                            otherDiv.style.display = 'none';
                        }
                    });
                });
                </script>
            <?php endif;
        }
        ?>

        <?php
        // Allow admin or shipper to update delivery status
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../../app/Auth.php';
        $isAdmin = \Auth::hasRole('admin');
        $isShipper = \Auth::hasRole('shipper');
        
        if ($isAdmin || $isShipper): ?>
            <div class="update-section">
                <h3 class="update-title">
                    <i class="bi bi-pencil-square"></i> 
                    <?= $isAdmin ? 'Cập nhật trạng thái đơn hàng (Admin)' : 'Cập nhật trạng thái giao hàng (Shipper)' ?>
                </h3>
                <form action="<?= APP_URL ?>/Home/shipperUpdateOrder" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" value="<?= intval($order['id']) ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <?php if ($isAdmin): ?>
                                <!-- Admin có thể chọn tất cả trạng thái -->
                                <option value="dang_chuan_bi">Đang chuẩn bị hàng</option>
                                <option value="da_giao_dvvc">Đã giao cho đơn vị vận chuyển</option>
                                <option value="da_nhan_hang">Đã nhận hàng</option>
                                <option value="da_tra_hang">Trả hàng</option>
                            <?php else: ?>
                                <!-- Shipper chỉ có thể giao và trả hàng -->
                                <option value="da_nhan_hang">Giao thành công</option>
                                <option value="da_tra_hang">Trả hàng</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <?php if ($isShipper && $deliveryStatus !== 'da_nhan_hang'): ?>
                        <div class="mb-3" id="reasonDiv" style="display:none;">
                            <label class="form-label">Lý do trả hàng</label>
                            <textarea name="return_reason" class="form-control" rows="3" placeholder="Ví dụ: Khách từ chối nhận, hàng bị hỏng..."></textarea>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check-circle"></i> Cập nhật
                    </button>
                </form>
                
                <?php if ($isShipper): ?>
                <script>
                    document.querySelector('select[name="status"]').addEventListener('change', function() {
                        const reasonDiv = document.getElementById('reasonDiv');
                        if (this.value === 'da_tra_hang') {
                            reasonDiv.style.display = 'block';
                        } else {
                            reasonDiv.style.display = 'none';
                        }
                    });
                </script>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php else: ?>
            <div class="alert alert-warning">Không tìm thấy thông tin đơn hàng.</div>
        <?php endif; ?>
    </div>
</div>
