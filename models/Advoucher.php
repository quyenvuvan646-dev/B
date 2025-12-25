<?php
require_once "BaseModel.php";

class Advoucher extends BaseModel
{
    private $table = "voucher";


    // Lấy tất cả voucher
    public function getAll()
    {
        $this->ensureChucNangColumnExists();
        $this->autoDisableExpiredVouchers();
        $sql = "SELECT * FROM voucher ORDER BY ngaybatdau DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 voucher theo ID
    public function getById($vc_id)
    {
        $this->ensureChucNangColumnExists();
        $sql = "SELECT * FROM voucher WHERE vc_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vc_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm voucher mới
    public function insert($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang = 'goc')
    {
        // Kiểm tra xem column chuc_nang tồn tại không, nếu không thì thêm vào
        $this->ensureChucNangColumnExists();
        
        $sql = "INSERT INTO voucher (vc_id, ngaybatdau, ngayketthuc, giatoithieu, giagiam, soluong, trangthai, chuc_nang)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang]);
    }

    // Cập nhật voucher
    public function update($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang = 'goc')
    {
        $this->ensureChucNangColumnExists();
        
        $sql = "UPDATE voucher SET ngaybatdau = ?, ngayketthuc = ?, giatoithieu = ?, giagiam = ?, soluong = ?, trangthai = ?, chuc_nang = ?
                WHERE vc_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang, $vc_id]);
    }

    // Xóa voucher
    public function deleteVoucher($vc_id)
    {
        $sql = "DELETE FROM voucher WHERE vc_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$vc_id]);
    }

    // Lấy voucher áp dụng được dựa trên danh sách sản phẩm (chỉ khi có sản phẩm đủ điều kiện)
    public function getApplicableVouchers($cartItems = [])
    {
        $this->ensureChucNangColumnExists();
        if (empty($cartItems)) {
            return [];
        }

        // Tính tổng tiền hàng trong giỏ
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += (float)$item['giaxuat'] * (int)($item['soluong'] ?? 1);
        }

        // Nếu không có sản phẩm nào đáp ứng, trả về rỗng
        if ($totalPrice == 0) {
            return [];
        }

        // Lấy voucher áp dụng được (giá tối thiểu <= tổng tiền hàng)
        $sql = "SELECT * FROM voucher 
                WHERE trangthai = 1 
                AND ngaybatdau <= NOW() 
                AND ngayketthuc >= NOW()
                AND giatoithieu <= ?
                AND soluong > 0
                ORDER BY giagiam DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$totalPrice]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra voucher có hợp lệ không (kiểm tra với giá cao nhất trong giỏ)
    public function isVoucherValid($vc_id, $cartItems = [])
    {
        if (empty($cartItems)) {
            return null;
        }

        // Tính tổng tiền hàng trong giỏ
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += (float)$item['giaxuat'] * (int)($item['soluong'] ?? 1);
        }

        $sql = "SELECT * FROM voucher 
            WHERE vc_id = ?
            AND trangthai = 1 
            AND ngaybatdau <= NOW() 
            AND ngayketthuc >= NOW()
            AND giatoithieu <= ?
            AND soluong > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vc_id, $totalPrice]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Giảm số lượng voucher khi sử dụng, đánh dấu hết nếu soluong = 0
    public function useVoucher($vc_id)
    {
        $sql = "UPDATE voucher SET soluong = soluong - 1 WHERE vc_id = ? AND soluong > 0";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$vc_id]);

        // Nếu còn lại 0 cái thì đánh dấu trạng thái = 0 (hết)
        if ($result) {
            $checkSql = "SELECT soluong FROM voucher WHERE vc_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$vc_id]);
            $voucher = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($voucher && $voucher['soluong'] == 0) {
                $updateStatusSql = "UPDATE voucher SET trangthai = 0 WHERE vc_id = ?";
                $updateStatusStmt = $this->db->prepare($updateStatusSql);
                $updateStatusStmt->execute([$vc_id]);
            }
        }

        return $result;
    }

    // Helper: Tự động tắt voucher hết hạn
    private function autoDisableExpiredVouchers()
    {
        try {
            $sql = "UPDATE voucher SET trangthai = 0 WHERE ngayketthuc < NOW() AND trangthai = 1";
            $this->db->exec($sql);
        } catch (Exception $e) {
            // Nếu có lỗi, bỏ qua
        }
    }

    // Helper: Đảm bảo cột chuc_nang tồn tại
    private function ensureChucNangColumnExists()
    {
        try {
            $checkSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='voucher' AND COLUMN_NAME='chuc_nang' AND TABLE_SCHEMA=DATABASE()";
            $checkStmt = $this->db->query($checkSql);
            $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$exists) {
                // Thêm cột nếu chưa tồn tại
                $alterSql = "ALTER TABLE voucher ADD COLUMN chuc_nang VARCHAR(10) DEFAULT 'goc'";
                $this->db->exec($alterSql);
            }
        } catch (Exception $e) {
            // Nếu có lỗi, bỏ qua (có thể cột đã tồn tại hoặc database không hỗ trợ)
        }
    }

    // Lấy voucher theo loại (ship hoặc goc)
    public function getVouchersByType($type = 'goc')
    {
        $this->ensureChucNangColumnExists();
        $this->autoDisableExpiredVouchers();
        
        $sql = "SELECT * FROM voucher 
                WHERE chuc_nang = ? 
                AND trangthai = 1 
                AND ngaybatdau <= NOW() 
                AND ngayketthuc >= NOW()
                AND soluong > 0
                ORDER BY giagiam DESC, ngaybatdau DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả voucher gốc (không phải ship)
    public function getGocVouchers()
    {
        return $this->getVouchersByType('goc');
    }

    // Lấy tất cả voucher ship
    public function getShipVouchers()
    {
        return $this->getVouchersByType('ship');
    }
}
