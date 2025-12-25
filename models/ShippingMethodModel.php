<?php
require_once 'BaseModel.php';

class ShippingMethodModel extends BaseModel {
    
    public function getAll() {
        $query = "SELECT * FROM shipping_methods ORDER BY id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM shipping_methods WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tính phí ship dựa trên khoảng cách (km) và shipping method
    public function calculateShippingFee($shippingMethodId, $distanceKm) {
        $method = $this->getById($shippingMethodId);
        if (!$method) {
            return 0;
        }
        $pricePerKm = (int)$method['price_per_km'];
        $fee = ceil($distanceKm * $pricePerKm); // làm tròn lên
        return $fee;
    }
}
?>
