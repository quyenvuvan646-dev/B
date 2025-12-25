<?php
require_once "BaseModel.php";

class AdKhuyenMai extends BaseModel
{
    private $table = "khuyenmai";

    public function getAllWithProduct()
    {
        $sql = "SELECT k.*, s.tensp, s.giaXuat, s.hinhanh
                FROM khuyenmai k
                LEFT JOIN tblsanpham s ON k.masp = s.masp
                ORDER BY k.ngaybatdau DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getView()
    {
        $sql = "SELECT maLoaiSP FROM tblloaisp;";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc)
    {
        $sql = "INSERT INTO khuyenmai (maLoaiSP, masp, phantram, ngaybatdau, ngayketthuc)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc]);
    }

    public function deleteKm($km_id)
    {
        $sql = "DELETE FROM khuyenmai WHERE km_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$km_id]);
    }

    public function getById($km_id)
    {
        $sql = "SELECT * FROM khuyenmai WHERE km_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$km_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateKm($km_id, $maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc)
    {
        $sql = "UPDATE khuyenmai SET maLoaiSP = ?, masp = ?, phantram = ?, ngaybatdau = ?, ngayketthuc = ? WHERE km_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc, $km_id]);
    }



    // trả về 1 record sản phẩm kèm phantram (ưu tiên bảng khuyenmai nếu đang hiệu lực)
    public function findWithDiscount($masp)
    {
        $sql = "
            SELECT 
                s.*,
                COALESCE(k.phantram, s.khuyenmai, 0) AS phantram,
                k.ngaybatdau,
                k.ngayketthuc
            FROM tblsanpham s
            LEFT JOIN khuyenmai k
                ON s.masp = k.masp
                AND NOW() BETWEEN k.ngaybatdau AND k.ngayketthuc
            WHERE s.masp = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$masp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getProductsByCategory($maLoaiSP)
    {
        $sql = "SELECT masp FROM tblsanpham WHERE maLoaiSP = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLoaiSP]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertForCategory($maLoaiSP, $phantram, $ngaybatdau, $ngayketthuc)
    {
        // Lấy tất cả sản phẩm thuộc loại
        $products = $this->getProductsByCategory($maLoaiSP);

        // Chuẩn bị statement để chèn nhiều dòng
        $sql = "INSERT INTO khuyenmai (maLoaiSP, masp, phantram, ngaybatdau, ngayketthuc)
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($products as $p) {
            $stmt->execute([
                $maLoaiSP,
                $p["masp"],
                $phantram,
                $ngaybatdau,
                $ngayketthuc
            ]);
        }

        return true;
    }
}
