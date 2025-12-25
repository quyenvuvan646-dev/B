<?php
// Simple shipper homepage
$user = $_SESSION['user'] ?? null;
?>
<div class="container mt-4">
    <h2>Trang Shipper</h2>
    <p>Xin chào, <?php echo htmlspecialchars($user['email'] ?? 'Shipper'); ?>.</p>
    <p>Nhanh chóng truy cập các tác vụ giao hàng:</p>
    <ul>
        <li><a href="<?php echo APP_URL; ?>/Admin/orders">Danh sách đơn hàng (Admin view)</a></li>
        <li><a href="<?php echo APP_URL; ?>/Home/order">Xem giỏ hàng (nếu cần)</a></li>
        <li><a href="<?php echo APP_URL; ?>/Home/orderHistory">Lịch sử đơn hàng</a></li>
    </ul>
    <hr>
    <h4>Đơn hàng gần nhất</h4>
    <p>Để xem/điều phối đơn hàng, vào chi tiết đơn:</p>
    <p><em>(Mở trang chi tiết đơn trong trang người dùng để cập nhật trạng thái giao hàng)</em></p>
</div>
