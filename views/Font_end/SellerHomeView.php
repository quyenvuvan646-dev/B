<?php
$user = $_SESSION['user'] ?? null;
?>
<div class="container mt-4">
    <h2>Trang Seller</h2>
    <p>Xin chào, <?php echo htmlspecialchars($user['email'] ?? 'Seller'); ?>.</p>
    <p>Các chức năng bán hàng:</p>
    <ul>
        <li><a href="<?php echo APP_URL; ?>/Admin/products">Quản lý sản phẩm</a></li>
        <li><a href="<?php echo APP_URL; ?>/Admin/statistics">Thống kê bán hàng</a></li>
        <li><a href="<?php echo APP_URL; ?>/Home/orderHistory">Đơn hàng liên quan</a></li>
    </ul>
</div>
