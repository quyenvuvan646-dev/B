<?php
class NotificationModel extends DB {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Tạo thông báo mới
    public function createNotification($type, $title, $message, $related_id = null, $related_type = null) {
        $sql = "INSERT INTO notifications (type, title, message, related_id, related_type, created_at) 
                VALUES (:type, :title, :message, :related_id, :related_type, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':type' => $type,
            ':title' => $title,
            ':message' => $message,
            ':related_id' => $related_id,
            ':related_type' => $related_type
        ]);
        
        return $this->db->lastInsertId();
    }
    
    // Lấy thông báo chưa đọc của admin
    public function getUnreadNotifications($limit = 10) {
        $sql = "SELECT * FROM notifications 
                WHERE is_read = 0 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Lấy tất cả thông báo
    public function getAllNotifications($limit = 20, $offset = 0) {
        $sql = "SELECT * FROM notifications 
                ORDER BY created_at DESC 
                LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Đánh dấu thông báo đã đọc
    public function markAsRead($notification_id) {
        $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $notification_id]);
        
        return $stmt->rowCount();
    }
    
    // Đánh dấu tất cả thông báo đã đọc
    public function markAllAsRead() {
        $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    // Đếm thông báo chưa đọc
    public function countUnread() {
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    // Xóa thông báo
    public function deleteNotification($notification_id) {
        $sql = "DELETE FROM notifications WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $notification_id]);
        
        return $stmt->rowCount();
    }
    
    // Lấy thông báo theo ID
    public function getNotificationById($notification_id) {
        $sql = "SELECT * FROM notifications WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $notification_id]);
        
        return $stmt->fetch();
    }
}
?>
