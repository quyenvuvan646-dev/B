<?php
require_once "BaseModel.php";
class AdProducModel extends BaseModel{
    private $table="tblsanpham";
    public function insert($maLoaiSP,$masp,$tensp,$hinhanh,$soluong,
    $giaNhap,$giaXuat,$khuyenmai,$mota,$createDate,$emailSeller) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($this->table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        // Kiểm tra xem mã  sản phẩm đã tồn tại chưa
        $column = $this->primaryKeys[$this->table];
        if($this->check($this->table, $column, $masp)>0){
            echo "Mã sản phẩm đã tồn tại. Vui lòng chọn mã khác.";
            return;
        }
        else{
            // Chuẩn bị câu lệnh INSERT
                $sql = "INSERT INTO tblsanpham (maLoaiSP,masp,tensp,hinhanh,soluong,
                giaNhap,giaXuat,khuyenmai,mota,createDate,email) 
                    VALUES (:maLoaiSP,:masp,:tensp,:hinhanh,:soluong,:giaNhap,
                    :giaXuat,:khuyenmai,:mota,:createDate,:email)";
            try {
                $stmt = $this->db->prepare($sql);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':maLoaiSP', $maLoaiSP);
                $stmt->bindParam(':masp', $masp);
                $stmt->bindParam(':tensp', $tensp);
                $stmt->bindParam(':hinhanh', $hinhanh);
                $stmt->bindParam(':soluong', $soluong);
                $stmt->bindParam(':giaNhap', $giaNhap);
                $stmt->bindParam(':giaXuat', $giaXuat);
                $stmt->bindParam(':khuyenmai', $khuyenmai);
                $stmt->bindParam(':mota', $mota);
                $stmt->bindParam(':createDate', $createDate);
                $stmt->bindParam(':email', $emailSeller);
                $stmt->execute();
                echo "Thêm sản phẩm thành công.";
            } catch (PDOException $e) {
                echo "Thất bại" . $e->getMessage();
            } 
        }    
    }
    
    public function update($maLoaiSP,$masp,$tensp,$hinhanh,$soluong,$giaNhap,
    $giaXuat,$khuyenmai,$mota,$createDate,$emailSeller) {
        // Chuẩn bị câu lệnh UPDATE
        $sql = "UPDATE tblsanpham SET 
                maLoaiSP = :maLoaiSP,
                masp = :masp, 
                tensp = :tensp,
                hinhanh = :hinhanh,
                soluong = :soluong,
                giaNhap = :giaNhap,
                giaXuat = :giaXuat,
                khuyenmai = :khuyenmai,
                mota = :mota,
                createDate = :createDate,
                email = :email
                WHERE masp = :masp";
        try {
            $stmt = $this->db->prepare($sql); 
            // Gán giá trị cho các tham số
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':masp', $masp);
            $stmt->bindParam(':tensp', $tensp);
            $stmt->bindParam(':hinhanh', $hinhanh);
            $stmt->bindParam(':soluong', $soluong);
            $stmt->bindParam(':giaNhap', $giaNhap);
            $stmt->bindParam(':giaXuat', $giaXuat);
            $stmt->bindParam(':khuyenmai', $khuyenmai);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':createDate', $createDate);
            $stmt->bindParam(':email', $emailSeller);
            // Thực thi câu lệnh
            $stmt->execute();
            //echo "Cập nhật loại sản phẩm thành công.";
        } catch (PDOException $e) {
            echo "Cập nhật không thành công: " . $e->getMessage();
        }
    }

    // Tìm kiếm sản phẩm theo tên hoặc mô tả, chỉ trả về hàng còn tồn (>0)
    public function searchProducts($keyword) {
        $kw = '%' . $keyword . '%';
        // Prioritize products where keyword appears in product name
        // Order: exact match first, then starts with, then contains
        $sql = "SELECT *, 
                CASE 
                    WHEN LOWER(tensp) = LOWER(:keyword_exact) THEN 1
                    WHEN LOWER(tensp) LIKE LOWER(:keyword_start) THEN 2
                    WHEN LOWER(tensp) LIKE LOWER(:kw1) THEN 3
                    WHEN LOWER(mota) LIKE LOWER(:kw2) THEN 4
                    ELSE 5
                END AS relevance
                FROM {$this->table} 
                WHERE (tensp LIKE :kw3 OR mota LIKE :kw4) 
                AND soluong > 0
                ORDER BY relevance ASC, tensp ASC";
        
        $stmt = $this->db->prepare($sql);
        $keyword_exact = $keyword;
        $keyword_start = $keyword . '%';
        $stmt->bindParam(':keyword_exact', $keyword_exact, PDO::PARAM_STR);
        $stmt->bindParam(':keyword_start', $keyword_start, PDO::PARAM_STR);
        $stmt->bindParam(':kw1', $kw, PDO::PARAM_STR);
        $stmt->bindParam(':kw2', $kw, PDO::PARAM_STR);
        $stmt->bindParam(':kw3', $kw, PDO::PARAM_STR);
        $stmt->bindParam(':kw4', $kw, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm shop (distributor) theo tên
    public function searchShops($keyword) {
        $kw = '%' . $keyword . '%';
        $sql = "SELECT DISTINCT 
                    u.user_id,
                    u.email,
                    u.fullname as shop_name,
                    u.phone,
                    u.address,
                    COUNT(DISTINCT p.masp) as product_count,
                    ROUND(AVG(c.rating), 2) as avg_rating
                FROM tbluser u
                LEFT JOIN tblsanpham p ON u.email = p.email
                LEFT JOIN comments c ON p.masp = c.masp AND c.rating IS NOT NULL
                WHERE u.user_role = 4
                AND (u.is_locked IS NULL OR u.is_locked = 0)
                AND (LOWER(u.fullname) LIKE LOWER(:kw1) OR LOWER(u.email) LIKE LOWER(:kw2))
                GROUP BY u.email
                ORDER BY product_count DESC, avg_rating DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':kw1', $kw, PDO::PARAM_STR);
        $stmt->bindParam(':kw2', $kw, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
