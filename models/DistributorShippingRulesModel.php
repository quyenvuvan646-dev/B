<?php
require_once __DIR__ . '/BaseModel.php';

class DistributorShippingRulesModel extends BaseModel {
    protected $table = 'distributor_shipping_rules';

    // Lấy tất cả rules của distributor
    public function getRulesByDistributor($distributorEmail) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table}
                WHERE distributor_email = ?
                ORDER BY distance_from ASC
            ");
            $stmt->execute([$distributorEmail]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug log
            error_log("getRulesByDistributor: email=$distributorEmail, count=" . count($result));
            
            return $result;
        } catch (Exception $e) {
            error_log("getRulesByDistributor ERROR: " . $e->getMessage());
            return [];
        }
    }

    // Lấy rule theo khoảng cách
    public function getRuleByDistance($distributorEmail, $distanceKm) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE distributor_email = ? 
            AND distance_from <= ? 
            AND distance_to >= ?
            LIMIT 1
        ");
        $stmt->execute([$distributorEmail, $distanceKm, $distanceKm]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm rule mới
    public function createRule($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (distributor_email, distance_from, distance_to, standard_fee, fast_fee, express_fee)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['distributor_email'],
            $data['distance_from'],
            $data['distance_to'],
            $data['standard_fee'],
            $data['fast_fee'],
            $data['express_fee']
        ]);
    }

    // Cập nhật rule
    public function updateRule($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET distance_from = ?,
                distance_to = ?,
                standard_fee = ?,
                fast_fee = ?,
                express_fee = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['distance_from'],
            $data['distance_to'],
            $data['standard_fee'],
            $data['fast_fee'],
            $data['express_fee'],
            $id
        ]);
    }

    // Xóa rule
    public function deleteRule($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Lấy phí vận chuyển + thời gian giao hàng dự kiến cho distributor
    public function getShippingFee($distributorEmail, $distanceKm, $shippingMethodId) {
        $rule = $this->getRuleByDistance($distributorEmail, $distanceKm);
        
        if (!$rule) {
            return null; // Không tìm thấy rule tùy chỉnh, dùng global
        }

        // Trả về phí dựa trên phương thức vận chuyển (price per km)
        $methodMap = [
            1 => 'standard_fee',    // Vận chuyển thường
            2 => 'fast_fee',        // Vận chuyển nhanh
            3 => 'express_fee'      // Hỏa tốc
        ];

        $daysMap = [
            1 => 'standard_days',   // Vận chuyển thường
            2 => 'fast_days',       // Vận chuyển nhanh (standard - 1)
            3 => 'express_days'     // Hỏa tốc (standard - 2)
        ];

        $feeColumn = $methodMap[$shippingMethodId] ?? 'standard_fee';
        $daysColumn = $daysMap[$shippingMethodId] ?? 'standard_days';
        
        $pricePerKm = $rule[$feeColumn] ?? null;
        $estimatedDays = $rule[$daysColumn] ?? 3;
        
        if ($pricePerKm === null) {
            return null;
        }
        
        // Tính tổng phí = price_per_km * distance
        $totalFee = ceil($distanceKm * $pricePerKm);
        
        // Trả về array với cả phí và thời gian
        return [
            'fee' => $totalFee,
            'days' => $estimatedDays
        ];
    }

    // Kiểm tra overlap ranges
    public function checkOverlap($distributorEmail, $distanceFrom, $distanceTo, $excludeId = null) {
        $sql = "
            SELECT COUNT(*) as count FROM {$this->table}
            WHERE distributor_email = ?
            AND NOT (distance_to < ? OR distance_from > ?)
        ";
        $params = [$distributorEmail, $distanceFrom, $distanceTo];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
?>
