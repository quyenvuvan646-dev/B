<div class="container py-4">
    <h2 class="mb-4">📦 Đơn hàng của bạn đã đăng bán</h2>
    <?php if (empty($data['orders'])): ?>
        <p class="alert alert-info">Chưa có đơn hàng nào.</p>
    <?php else: ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã hóa đơn</th>
                <th>Người mua</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Trạng thái giao hàng</th>
                <th>Thanh toán</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['orders'] as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_code']) ?></td>
                    <td><?= htmlspecialchars($order['user_email']) ?></td>
                    <td><?= number_format($order['total_amount'],0,',','.') ?>₫</td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td>
                        <?php 
                            $status = $order['delivery_status'] ?? 'Chưa xử lý';
                            $statusMap = [
                                'cho_xac_nhan' => 'Chờ xác nhận',
                                'da_giao_dvvc' => 'Đã giao cho ĐVVC',
                                'da_nhan_hang' => 'Đã nhận hàng',
                                'da_tra_hang' => 'Đã trả hàng',
                                'da_huy' => 'Đã hủy'
                            ];
                            echo htmlspecialchars($statusMap[$status] ?? $status);
                        ?>
                    </td>
                    <td><?= htmlspecialchars($order['transaction_info']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/Distributor/orderDetail/<?= $order['id'] ?>" class="btn btn-sm btn-info">Xem & Cập nhật</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
