<?php
require_once "BaseModel.php";
class MessageModel extends BaseModel {
    protected $table = "messages";

    public function getMessagesByOrder($order_id) {
        $sql = "SELECT * FROM messages WHERE order_id = :order_id ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($order_id, $sender_email, $receiver_email, $message, $image_url = null, $type = 'user') {
        // Use admin email as fallback if sender_email is NULL
        if (empty($sender_email)) {
            $sender_email = 'admin@ecommerce.com'; // Default admin email
        }
        $sql = "INSERT INTO messages (order_id, sender_email, receiver_email, message, image_url, type, is_read) VALUES (:order_id, :sender_email, :receiver_email, :message, :image_url, :type, 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":sender_email", $sender_email);
        $stmt->bindParam(":receiver_email", $receiver_email);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":image_url", $image_url);
        $stmt->bindParam(":type", $type);
        return $stmt->execute();
    }

    // Get count of unread messages for a user
    public function getUnreadCount($email) {
        $sql = "SELECT COUNT(*) as count FROM messages WHERE receiver_email = :email AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    // Get orders with unread message count
    public function getOrdersWithUnreadCount($email) {
        $sql = "SELECT m.order_id, COUNT(*) as unread_count 
                FROM messages m 
                WHERE m.receiver_email = :email AND m.is_read = 0 
                GROUP BY m.order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark all messages in an order as read for a user
    public function markOrderMessagesAsRead($order_id, $email) {
        $sql = "UPDATE messages SET is_read = 1 WHERE order_id = :order_id AND receiver_email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }
}
