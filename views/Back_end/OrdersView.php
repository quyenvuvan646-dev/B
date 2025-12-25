<?php
$orders = $data['orders'] ?? [];
?>
<div class="container p-3">
    <h3>Danh sách đơn hàng</h3>
    <div class="mb-3">
        <small class="text-muted">Hiển thị tất cả đơn hàng. Sử dụng cột "transaction_info" để phân biệt COD / Thanh toán online / Đã thanh toán.</small>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Ngày tạo</th>
                <th>Thanh toán</th>
                <th>Trạng thái giao</th>
                <th>Đã thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?php echo htmlspecialchars($o['id']); ?></td>
                    <td><?php echo htmlspecialchars($o['order_code']); ?></td>
                    <td><?php echo htmlspecialchars($o['user_email']); ?></td>
                    <td><?php echo number_format($o['total_amount'] ?? 0,0,',','.'); ?> ₫</td>
                    <td><?php echo htmlspecialchars($o['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($o['transaction_info'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($o['delivery_status'] ?? ''); ?></td>
                    <td><?php echo (!empty($o['is_paid']) && $o['is_paid']) ? 'Có' : 'Chưa'; ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/Home/orderDetail/<?= $o['id'] ?>" class="btn btn-sm btn-info mb-1">Xem chi tiết</a>
                        <form action="<?= APP_URL ?>/Home/shipperUpdateOrder" method="POST" class="d-inline-block">
                            <input type="hidden" name="order_id" value="<?= intval($o['id']) ?>">
                            <select name="status" class="form-select form-select-sm mb-1" style="width:170px; display:inline-block;">
                                <option value="">-- Cập nhật trạng thái --</option>
                                <option value="dang_chuan_bi">Đang chuẩn bị hàng</option>
                                <option value="da_giao_dvvc">Đã giao cho đơn vị vận chuyển</option>
                                <option value="da_nhan_hang">Đã nhận hàng</option>
                                <option value="da_tra_hang">Trả hàng</option>
                            </select>
                            <div class="return-reason" style="display:none;">
                                <input type="text" name="return_reason" class="form-control form-control-sm mb-1" placeholder="Lý do trả hàng (nếu có)">
                            </div>
                            <button class="btn btn-sm btn-primary">Cập nhật</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    // Show return reason input when admin selects 'Trả hàng'
    document.querySelectorAll('select[name="status"]').forEach(function(sel){
        sel.addEventListener('change', function(){
            var rr = this.closest('form').querySelector('.return-reason');
            if(this.value === 'da_tra_hang') rr.style.display = 'block'; else rr.style.display = 'none';
        });
    });
</script>