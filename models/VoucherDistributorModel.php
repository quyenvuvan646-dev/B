<?php
require_once 'BaseModel.php';

class VoucherDistributorModel extends BaseModel {
    protected $table = 'voucher_distributor';

    /**
     * Lấy tất cả voucher của distributor
     */
    public function getVouchersByDistributor($distributorEmail) {
        $sql = "SELECT * FROM {$this->table} WHERE distributor_email = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$distributorEmail]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy voucher theo code
     */
    public function getVoucherByCode($code) {
        $sql = "SELECT * FROM {$this->table} WHERE code = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy voucher theo ID
     */
    public function getVoucherById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo voucher mới
     */
    public function createVoucher($data) {
        $sql = "INSERT INTO {$this->table} 
                (distributor_email, code, discount_value, min_amount, quantity, status, time_start, time_end) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['distributor_email'],
                $data['code'],
                $data['discount_value'],
                $data['min_amount'],
                $data['quantity'],
                $data['status'],
                $data['time_start'],
                $data['time_end']
            ]);

            if ($result) {
                return ['success' => true, 'message' => 'Tạo voucher thành công', 'id' => $this->db->lastInsertId()];
            } else {
                return ['success' => false, 'message' => 'Lỗi tạo voucher'];
            }
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Cập nhật voucher
     */
    public function updateVoucher($id, $data) {
        $fields = [];
        $params = [];

        if (isset($data['code'])) {
            $fields[] = "code = ?";
            $params[] = $data['code'];
        }
        if (isset($data['discount_value'])) {
            $fields[] = "discount_value = ?";
            $params[] = $data['discount_value'];
        }
        if (isset($data['min_amount'])) {
            $fields[] = "min_amount = ?";
            $params[] = $data['min_amount'];
        }
        if (isset($data['quantity'])) {
            $fields[] = "quantity = ?";
            $params[] = $data['quantity'];
        }
        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
        }
        if (isset($data['time_start'])) {
            $fields[] = "time_start = ?";
            $params[] = $data['time_start'];
        }
        if (isset($data['time_end'])) {
            $fields[] = "time_end = ?";
            $params[] = $data['time_end'];
        }

        if (empty($fields)) {
            return ['success' => false, 'message' => 'Không có dữ liệu để cập nhật'];
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                return ['success' => true, 'message' => 'Cập nhật voucher thành công'];
            } else {
                return ['success' => false, 'message' => 'Lỗi cập nhật voucher'];
            }
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Xóa voucher
     */
    public function deleteVoucher($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$id]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Xóa voucher thành công'];
            } else {
                return ['success' => false, 'message' => 'Lỗi xóa voucher'];
            }
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sử dụng voucher - tăng quantity_used
     */
    public function useVoucher($voucherId) {
        $sql = "UPDATE {$this->table} SET quantity_used = quantity_used + 1 WHERE id = ? AND quantity_used < quantity";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$voucherId]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Voucher đã được sử dụng'];
            } else {
                return ['success' => false, 'message' => 'Không thể sử dụng voucher'];
            }
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kiểm tra voucher có hợp lệ không
     */
    public function validateVoucher($code, $cartTotal) {
        $voucher = $this->getVoucherByCode($code);
        
        if (!$voucher) {
            return ['valid' => false, 'message' => 'Voucher không tồn tại'];
        }

        if ($voucher['status'] !== 'active') {
            return ['valid' => false, 'message' => 'Voucher không hoạt động'];
        }

        $now = date('Y-m-d H:i:s');
        if ($now < $voucher['time_start'] || $now > $voucher['time_end']) {
            return ['valid' => false, 'message' => 'Voucher hết hạn'];
        }

        if ($voucher['quantity_used'] >= $voucher['quantity']) {
            return ['valid' => false, 'message' => 'Voucher đã hết số lượng'];
        }

        if ($cartTotal < $voucher['min_amount']) {
            return ['valid' => false, 'message' => 'Đơn hàng chưa đủ ' . number_format($voucher['min_amount']) . '₫'];
        }

        return [
            'valid' => true,
            'message' => 'Voucher hợp lệ',
            'voucher' => $voucher,
            'discount' => $voucher['discount_value']
        ];
    }

    /**
     * Đánh dấu sử dụng voucher bằng code (dùng khi code đã được xác thực ở bước trước)
     */
    public function useVoucherByCode($code) {
        $voucher = $this->getVoucherByCode($code);
        if (!$voucher) {
            return ['success' => false, 'message' => 'Voucher không tồn tại'];
        }
        return $this->useVoucher($voucher['id']);
    }

    /**
     * Lấy voucher khả dụng của distributor cho giỏ hàng
     */
    public function getAvailableVouchers($distributorEmail) {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM {$this->table} 
                WHERE distributor_email = ? 
                AND status = 'active'
                AND time_start <= ?
                AND time_end >= ?
                AND quantity_used < quantity
                ORDER BY discount_value DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$distributorEmail, $now, $now]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }
}
?>
