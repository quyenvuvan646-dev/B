<?php
require_once 'BaseModel.php';

class SupportTicketModel extends BaseModel {
    protected $table = 'support_tickets';
    
    // Create a new support ticket
    public function createTicket($userEmail, $message, $status = 'open') {
        if (empty($userEmail) || empty($message)) {
            throw new Exception('Email và message không được để trống');
        }
        
        $sql = "INSERT INTO {$this->table} (user_email, message, status, created_at)
                VALUES (?, ?, ?, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$userEmail, $message, $status]);
        
        if ($result) {
            $lastId = $this->db->lastInsertId();
            if (!$lastId) {
                throw new Exception('Không thể lấy ID của ticket vừa tạo');
            }
            return $lastId;
        }
        
        throw new Exception('Lỗi khi thực thi câu lệnh SQL');
    }
    
    // Get tickets by user email
    public function getTicketsByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE user_email = ? ORDER BY created_at DESC";
        return $this->select($sql, [$email]);
    }
    
    // Get all open tickets (for admin)
    public function getOpenTickets() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'open' ORDER BY created_at DESC";
        return $this->select($sql, []);
    }
    
    // Get ticket by ID
    public function getTicketById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    // Update ticket status
    public function updateTicketStatus($id, $status, $adminResponse = null) {
        $sql = "UPDATE {$this->table} SET status = ?, admin_response = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $adminResponse, $id]);
    }
    
    // Add admin response to ticket
    public function addAdminResponse($id, $response) {
        $sql = "UPDATE {$this->table} SET admin_response = ?, status = 'closed', updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$response, $id]);
    }
    
    // Mark ticket as read by user
    public function markAsReadByUser($id) {
        $sql = "UPDATE {$this->table} SET user_read_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Get unread response count for user
    public function getUnreadResponseCount($email) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_email = ? 
                AND admin_response IS NOT NULL 
                AND admin_response != '' 
                AND user_read_at IS NULL";
        $result = $this->select($sql, [$email]);
        return $result ? (int)$result[0]['count'] : 0;
    }
}
