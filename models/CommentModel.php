<?php
require_once 'BaseModel.php';

class CommentModel extends BaseModel
{
    protected $table = 'comments';

    // Thêm bình luận/đánh giá (có hỗ trợ ảnh)
    public function addComment($masp, $user_email, $user_name, $rating, $content, $order_id = null, $parent_id = null, $image = null, $isVisible = true)
    {
        $sql = "INSERT INTO comments (masp, user_email, user_name, rating, content, image, `order_id`, parent_id, is_visible, created_at)
                VALUES (:masp, :user_email, :user_name, :rating, :content, :image, :order_id, :parent_id, :is_visible, :created_at)";
        $params = [
            ':masp' => $masp,
            ':user_email' => $user_email,
            ':user_name' => $user_name,
            ':rating' => (int)$rating,
            ':content' => $content,
            ':image' => $image,
            ':order_id' => $order_id,
            ':parent_id' => $parent_id,
            ':is_visible' => $isVisible ? 1 : 0,
            ':created_at' => date('Y-m-d H:i:s')
        ];
        $this->query($sql, $params);
        return $this->getLastInsertId();
    }

    // Lấy bình luận theo sản phẩm (chỉ hiển thị is_visible = 1 và sắp xếp theo thời gian)
    public function getCommentsByProduct($masp)
    {
        $sql = "SELECT * FROM comments WHERE masp = ? AND is_visible = 1 AND is_deleted = 0 ORDER BY parent_id IS NOT NULL, created_at DESC";
        return $this->select($sql, [$masp]);
    }

    // Kiểm tra user đã đánh giá sản phẩm chưa (đánh giá chính, không phải reply)
    public function userHasReviewedProduct($user_email, $masp)
    {
        $sql = "SELECT COUNT(*) as cnt FROM comments WHERE user_email = ? AND masp = ? AND parent_id IS NULL AND is_deleted = 0";
        $res = $this->select($sql, [$user_email, $masp]);
        return isset($res[0]['cnt']) && $res[0]['cnt'] > 0;
    }

    // Kiểm tra user đã mua sản phẩm (dựa trên orders + order_details)
    public function userHasBoughtProduct($user_email, $masp)
    {
        // A user is eligible to review if they purchased and paid for the product
        // Align with codebase: use is_paid = 1, and ensure the product appears in order_details
        $sql = "SELECT COUNT(*) as cnt 
                FROM orders o 
                JOIN order_details od ON o.id = od.order_id 
                WHERE o.user_email = ? 
                  AND od.product_id = ? 
                  AND o.is_paid = 1";
        $res = $this->select($sql, [$user_email, $masp]);
        return isset($res[0]['cnt']) && $res[0]['cnt'] > 0;
    }

    // Admin: lấy danh sách bình luận (kèm thông tin sản phẩm)
    public function listAllComments()
    {
        $sql = "SELECT c.*, sp.tensp FROM comments c LEFT JOIN tblsanpham sp ON c.masp = sp.masp WHERE c.is_deleted = 0 ORDER BY c.created_at DESC";
        return $this->select($sql, []);
    }

    // Admin actions
    public function setVisibility($id, $visible)
    {
        $sql = "UPDATE comments SET is_visible = :v WHERE id = :id";
        return $this->query($sql, [':v' => $visible ? 1 : 0, ':id' => $id]);
    }

    // Soft delete: đánh dấu comment là deleted
    public function deleteComment($id)
    {
        $sql = "UPDATE comments SET is_deleted = 1 WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    // Cập nhật comment (chỉnh sửa content và/hoặc image)
    public function updateComment($id, $content, $image = null)
    {
        $sets = ['content = :content'];
        $params = [':id' => $id, ':content' => $content];
        
        if ($image !== null) {
            $sets[] = 'image = :image';
            $params[':image'] = $image;
        }

        $sql = "UPDATE comments SET " . implode(', ', $sets) . " WHERE id = :id";
        return $this->query($sql, $params);
    }

    // Lấy comment theo ID
    public function getCommentById($id)
    {
        $sql = "SELECT * FROM comments WHERE id = ? AND is_deleted = 0";
        $result = $this->select($sql, [$id]);
        return !empty($result) ? $result[0] : null;
    }

    // Lấy tất cả reply của một comment (chỉ visible và không deleted)
    public function getRepliesByCommentId($parent_id)
    {
        $sql = "SELECT * FROM comments WHERE parent_id = ? AND is_visible = 1 AND is_deleted = 0 ORDER BY created_at ASC";
        return $this->select($sql, [$parent_id]);
    }

    // Reply to comment (user hoặc admin có thể reply)
    public function replyToComment($parent_id, $user_email, $user_name, $content, $image = null, $isVisible = true)
    {
        // Get parent comment to retrieve masp
        $parent = $this->getCommentById($parent_id);
        if (!$parent) return false;

        $sql = "INSERT INTO comments (masp, user_email, user_name, rating, content, image, parent_id, is_visible, created_at)
                VALUES (:masp, :user_email, :user_name, NULL, :content, :image, :parent_id, :is_visible, :created_at)";
        $params = [
            ':masp' => $parent['masp'],
            ':user_email' => $user_email,
            ':user_name' => $user_name,
            ':content' => $content,
            ':image' => $image,
            ':parent_id' => $parent_id,
            ':is_visible' => $isVisible ? 1 : 0,
            ':created_at' => date('Y-m-d H:i:s')
        ];
        $this->query($sql, $params);
        return $this->getLastInsertId();
    }

    // Kiểm tra user có phải người tác giả của comment này không
    public function isCommentAuthor($id, $user_email)
    {
        $sql = "SELECT COUNT(*) as cnt FROM comments WHERE id = ? AND user_email = ? AND parent_id IS NULL";
        $res = $this->select($sql, [$id, $user_email]);
        return isset($res[0]['cnt']) && $res[0]['cnt'] > 0;
    }

    // Kiểm tra user có thể reply vào comment này không (phải là tác giả comment chính)
    public function canReplyToComment($parent_id, $user_email)
    {
        $parent = $this->getCommentById($parent_id);
        if (!$parent) return false;

        // Chỉ tác giả comment chính có thể trả lời
        return $parent['user_email'] === $user_email;
    }
}

?>