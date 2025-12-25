<?php
require_once 'BaseModel.php';

class DistributorOrderModel extends BaseModel {
    // Lấy đơn hàng chứa sản phẩm do distributor đăng bán
    public function getOrdersByDistributor($email) {
        $sql = "SELECT DISTINCT o.* FROM orders o
            JOIN order_details od ON o.id = od.order_id
            JOIN tblsanpham p ON od.product_id = p.masp
            WHERE p.email = :email
            ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
