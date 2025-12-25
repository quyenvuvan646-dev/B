<?php
require_once __DIR__ . '/BaseModel.php';

class FavoriteModel extends BaseModel
{
    protected $table = 'favorites';

    public function add($userEmail, $productCode)
    {
        $sql = "INSERT INTO {$this->table} (user_email, product_code) VALUES (:user_email, :product_code)
                ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_email' => $userEmail,
            ':product_code' => $productCode
        ]);
        return true;
    }

    public function remove($userEmail, $productCode)
    {
        $sql = "DELETE FROM {$this->table} WHERE user_email = :user_email AND product_code = :product_code";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_email' => $userEmail,
            ':product_code' => $productCode
        ]);
    }

    public function isFavorite($userEmail, $productCode)
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE user_email = :user_email AND product_code = :product_code LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_email' => $userEmail,
            ':product_code' => $productCode
        ]);
        return (bool)$stmt->fetchColumn();
    }

    public function toggle($userEmail, $productCode)
    {
        if ($this->isFavorite($userEmail, $productCode)) {
            $this->remove($userEmail, $productCode);
            return false;
        }
        $this->add($userEmail, $productCode);
        return true;
    }

    public function getFavoriteCodes($userEmail)
    {
        $sql = "SELECT product_code FROM {$this->table} WHERE user_email = :user_email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_email' => $userEmail]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'product_code');
    }

    public function getFavoritesWithProducts($userEmail)
    {
        $sql = "SELECT f.product_code, f.created_at, p.*
                FROM {$this->table} f
                JOIN tblsanpham p ON p.masp = f.product_code
                WHERE f.user_email = :user_email
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_email' => $userEmail]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
