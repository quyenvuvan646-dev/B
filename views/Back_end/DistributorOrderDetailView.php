<div class="container py-4">
    <h2 class="mb-4">📝 Chi tiết đơn hàng #<?= htmlspecialchars($data['order']['order_code']) ?></h2>
    <div class="card mb-3">
        <div class="card-body">
            <strong>Người mua:</strong> <?= htmlspecialchars($data['order']['user_email']) ?><br>
            <strong>Người nhận:</strong> <?= htmlspecialchars($data['order']['receiver'] ?? 'N/A') ?><br>
            <strong>SĐT:</strong> <?= htmlspecialchars($data['order']['phone'] ?? 'N/A') ?><br>
            <strong>Địa chỉ:</strong> <?= htmlspecialchars($data['order']['address'] ?? 'N/A') ?><br>
            <strong>Ngày đặt:</strong> <?= htmlspecialchars($data['order']['created_at']) ?><br>
            <strong>Tổng tiền:</strong> <?= number_format($data['order']['total_amount'],0,',','.') ?>₫<br>
            <strong>Trạng thái giao hàng:</strong> 
            <?php 
                $status = $data['order']['delivery_status'] ?? 'Chưa xử lý';
                $statusMap = [
                    'cho_xac_nhan' => 'Chờ xác nhận',
                    'da_giao_dvvc' => 'Đã giao cho đơn vị vận chuyển',
                    'da_nhan_hang' => 'Đã nhận hàng',
                    'da_tra_hang' => 'Đã trả hàng',
                    'da_huy' => 'Đã hủy'
                ];
                echo htmlspecialchars($statusMap[$status] ?? $status);
            ?><br>
            <strong>Trạng thái thanh toán:</strong> <?= htmlspecialchars($data['order']['transaction_info']) ?>
        </div>
    </div>
    <h5>Sản phẩm trong đơn hàng:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá xuất</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['orderItems'] as $item): ?>
                <tr>
                    <td><img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($item['image'] ?? $item['hinhanh'] ?? '') ?>" style="max-width:60px;"></td>
                    <td><?= htmlspecialchars($item['product_name'] ?? $item['tensp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= number_format($item['sale_price'] ?? $item['price'] ?? 0,0,',','.') ?>₫</td>
                    <td><?= number_format($item['total'] ?? (($item['sale_price'] ?? $item['price'] ?? 0) * $item['quantity']),0,',','.') ?>₫</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h5>Cập nhật trạng thái đơn hàng:</h5>
    <form method="POST" action="<?= APP_URL ?>/Distributor/updateOrderStatus/<?= $data['order']['id'] ?>">
        <div class="mb-3">
            <select name="delivery_status" class="form-select" required>
                <option value="">-- Chọn trạng thái --</option>
                <option value="cho_xac_nhan" <?= ($data['order']['delivery_status'] ?? '') === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                <option value="da_giao_dvvc" <?= ($data['order']['delivery_status'] ?? '') === 'da_giao_dvvc' ? 'selected' : '' ?>>Đã giao cho đơn vị vận chuyển</option>
                <option value="da_nhan_hang" <?= ($data['order']['delivery_status'] ?? '') === 'da_nhan_hang' ? 'selected' : '' ?>>Đã nhận hàng</option>
                <option value="da_tra_hang" <?= ($data['order']['delivery_status'] ?? '') === 'da_tra_hang' ? 'selected' : '' ?>>Đã trả hàng</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
        <a href="<?= APP_URL ?>/Distributor/orders" class="btn btn-secondary">Quay lại danh sách</a>
    </form>
</div>
