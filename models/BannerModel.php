<?php
require_once 'BaseModel.php';

class BannerModel extends BaseModel
{
    protected $table = 'banners';

    // Get all active banners ordered by display_order
    public function getActiveBanners()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC";
        return $this->select($sql, []);
    }

    // Get all banners for admin (including inactive)
    public function getAllBanners()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY display_order ASC";
        return $this->select($sql, []);
    }

    // Get banner by ID
    public function getBannerById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }

    // Create new banner
    public function createBanner($title, $imagePath, $linkUrl, $displayOrder, $isActive = 1)
    {
        $sql = "INSERT INTO {$this->table} (title, image_path, link_url, display_order, is_active) 
                VALUES (?, ?, ?, ?, ?)";
        $this->query($sql, [$title, $imagePath, $linkUrl, $displayOrder, $isActive]);
        return $this->getLastInsertId();
    }

    // Update banner
    public function updateBanner($id, $title, $imagePath, $linkUrl, $displayOrder, $isActive)
    {
        $sql = "UPDATE {$this->table} 
                SET title = ?, image_path = ?, link_url = ?, display_order = ?, is_active = ? 
                WHERE id = ?";
        $this->query($sql, [$title, $imagePath, $linkUrl, $displayOrder, $isActive, $id]);
        return true;
    }

    // Delete banner
    public function deleteBanner($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $banner = $this->getBannerById($id);
        if ($banner) {
            $newStatus = $banner['is_active'] ? 0 : 1;
            $sql = "UPDATE {$this->table} SET is_active = ? WHERE id = ?";
            $this->query($sql, [$newStatus, $id]);
            return true;
        }
        return false;
    }

    // Update display order
    public function updateDisplayOrder($id, $order)
    {
        $sql = "UPDATE {$this->table} SET display_order = ? WHERE id = ?";
        $this->query($sql, [$order, $id]);
        return true;
    }
}
