<?php
// require_once 'BaseModel.php';
class OrderDetailModel extends BaseModel {
     protected $table = 'order_details';
    public function addOrderDetail($orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productName) {
        if (is_null($orderId)) {
            throw new Exception("orderId không được để trống khi thêm chi tiết đơn hàng.");
        }

        $sql = "INSERT INTO {$this->table} 
                (order_id, product_id, quantity, price, sale_price, total, image, product_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->query($sql, [
            $orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productName
        ]);
    }

    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetailsByOrderId($orderId) {
        $sql = "SELECT * FROM {$this->table} WHERE order_id = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
