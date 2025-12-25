<?php
class CartModel extends DB
{
    // Lấy danh sách sản phẩm trong giỏ hàng theo email


    public function addOrUpdateCart($email, $masp, $soluong, $gia)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("SELECT soluong FROM cart WHERE email = ? AND masp = ?");
        $stmt->execute([$email, $masp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // ✅ SET soluong bằng giá trị mới, không cộng dồn
            $stmt = $pdo->prepare("UPDATE cart SET soluong = ?, gia = ? WHERE email = ? AND masp = ?");
            $stmt->execute([$soluong, $gia, $email, $masp]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (email, masp, soluong, gia) VALUES (?, ?, ?, ?)");
            $stmt->execute([$email, $masp, $soluong, $gia]);
        }

        return true;
    }
    public function deleteCartItem($email, $masp)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("DELETE FROM cart WHERE email = ? AND masp = ?");
        $stmt->execute([$email, $masp]);
        return true;
    }

    public function updateCartQty($email, $masp, $qty)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("UPDATE cart SET soluong = ? WHERE email = ? AND masp = ?");
        $stmt->execute([$qty, $email, $masp]);
        return true;
    }
    public function updateNoteCart($email, $masp, $note_cart)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("UPDATE cart SET note_cart = ? WHERE email = ? AND masp = ?");
        $stmt->execute([$note_cart, $email, $masp]);
        return true;
    }

    /**
     * Lấy toàn bộ giỏ hàng theo email (tất cả sản phẩm)
     */
    public function getCartByEmail($email)
    {
        $pdo = $this->db ?? $this->con ?? null;
        $stmt = $pdo->prepare("SELECT c.masp, c.soluong AS qty, c.gia, c.note_cart, 
                                      s.tensp, s.hinhanh, s.giaXuat, s.email as product_seller
                           FROM cart c 
                           JOIN tblsanpham s ON c.masp = s.masp 
                           WHERE c.email = ?");
        $stmt->execute([$email]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cart = [];
        foreach ($rows as $row) {
            $cart[$row['masp']] = [
                'masp' => $row['masp'],
                'tensp' => $row['tensp'],
                'hinhanh' => $row['hinhanh'],
                'giaxuat' => $row['giaXuat'],
                'qty' => (int)$row['qty'],
                'phantram' => 0,
                'note_cart' => (int)$row['note_cart'],
                'product_seller' => $row['product_seller'] ?? null
            ];
        }
        return $cart;
    }

    /**
     * Lấy giỏ hàng chỉ gồm sản phẩm được chọn (note_cart = 1)
     */
    public function getSelectedCart($email)
    {
        $pdo = $this->db ?? $this->con ?? null;
        $stmt = $pdo->prepare("SELECT c.masp, c.soluong AS qty, c.gia, c.note_cart, 
                                      s.tensp, s.hinhanh, s.giaXuat, s.email as product_seller
                           FROM cart c 
                           JOIN tblsanpham s ON c.masp = s.masp 
                           WHERE c.email = ? AND c.note_cart = 1");
        $stmt->execute([$email]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cart = [];
        foreach ($rows as $row) {
            $cart[$row['masp']] = [
                'masp' => $row['masp'],
                'tensp' => $row['tensp'],
                'hinhanh' => $row['hinhanh'],
                'giaxuat' => $row['giaXuat'],
                'qty' => (int)$row['qty'],
                'phantram' => 0,
                'note_cart' => (int)$row['note_cart'],
                'product_seller' => $row['product_seller'] ?? null
            ];
        }
        return $cart;
    }
    public function getCartItem($email, $masp)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("SELECT * FROM cart WHERE email = ? AND masp = ?");
        $stmt->execute([$email, $masp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Xóa toàn bộ giỏ hàng của một email
    public function deleteAllCartByEmail($email)
    {
        $pdo = $this->db ?? $this->con ?? null;
        if (!$pdo) {
            throw new Exception("Không tìm thấy kết nối PDO trong DB");
        }

        $stmt = $pdo->prepare("DELETE FROM cart WHERE email = ?");
        $stmt->execute([$email]);
        return true;
    }
}
