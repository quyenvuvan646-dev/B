<?php
// Admin: Xem danh sách đơn hàng bị trả
$returnedOrders = $data['returnedOrders'] ?? [];
?>

<div class="container-fluid py-4">
    <h3 class="text-danger mb-4">
        <i class="bi bi-exclamation-triangle"></i> Quản lý đơn hàng bị trả
    </h3>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Danh sách đơn hàng bị trả lại</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng (Email)</th>
                            <th>Người nhận</th>
                            <th>Số tiền</th>
                            <th>Ngày đặt hàng</th>
                            <th>Ngày trả hàng</th>
                            <th>Lý do trả hàng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($returnedOrders)) {
                            $i = 1;
                            foreach ($returnedOrders as $order) {
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <a href="<?= APP_URL ?>/Home/orderDetail/<?= $order['id'] ?>" class="text-decoration-none">
                                        <strong><?= htmlspecialchars($order['order_code']) ?></strong>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($order['user_email']) ?></td>
                                <td><?= htmlspecialchars($order['receiver']) ?></td>
                                <td>
                                    <strong><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</strong>
                                </td>
                                <td><?= htmlspecialchars($order['created_at']) ?></td>
                                <td>
                                    <span class="text-danger">
                                        <?= htmlspecialchars($order['return_date']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;">
                                        <small><?= htmlspecialchars($order['return_reason'] ?? 'Không có lý do') ?></small>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= APP_URL ?>/Home/orderDetail/<?= $order['id'] ?>" class="btn btn-info btn-sm">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Không có đơn hàng bị trả lại nào.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= APP_URL ?>/Admin" class="btn btn-secondary">← Quay lại dashboard</a>
    </div>
</div>
