<?php
require_once __DIR__ . '/../models/MessageModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
class MessageHelper {
    public static function sendAutoOrderMessage($orderId) {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);
        $orderItems = $orderModel->getOrderItemsWithProduct($orderId);
        if (!$order || empty($orderItems)) return;
        $sellerEmail = $orderItems[0]['seller_email'] ?? null;
        $buyerEmail = $order['user_email'] ?? null;
        $orderCode = $order['order_code'] ?? '';
        $totalAmount = $order['total_amount'] ?? 0;
        $productName = $orderItems[0]['tensp'] ?? '';
        $productImage = $orderItems[0]['hinhanh'] ?? '';
        $msgModel = new MessageModel();
        $message = "Bạn đã đặt sản phẩm: $productName\nMã hóa đơn: $orderCode\nGiá: " . number_format($totalAmount,0,',','.') . "₫";
        $msgModel->sendMessage($orderId, $sellerEmail, $buyerEmail, $message, $productImage, 'auto');
    }
}
