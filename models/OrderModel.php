<?php
require_once 'BaseModel.php';
class OrderModel extends BaseModel
{
    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetailsByOrderId($orderId)
    {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    // Lưu đơn hàng kèm thông tin giao hàng
    protected $table = 'orders';
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = ? ORDER BY created_at DESC";
        return $this->select($sql, [$userId]);
    }
    public function updateOrderStatus($orderCode, $status)
    {
        // If payment completed via VNPAY, mark the order as paid as well
        if ($status === 'dathanhtoan') {
            $sql = "UPDATE orders SET transaction_info = :transaction_info, is_paid = 1 WHERE order_code = :orderCode";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':transaction_info' => $status,
                ':orderCode' => $orderCode
            ]);
        }

        $sql = "UPDATE orders SET transaction_info = :transaction_info WHERE order_code = :orderCode";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':transaction_info' => $status,
            ':orderCode' => $orderCode
        ]);
    }

    // Set delivery status and optional photo; optionally mark paid
    public function setDeliveryStatusById($orderId, $status, $photoPath = null, $markPaid = false)
    {
        // If order is marked as delivered, ensure it is marked paid by default
        if ($status === 'da_nhan_hang') {
            $markPaid = true;
        }
        $sets = [];
        $params = [':id' => $orderId];
        $sets[] = 'delivery_status = :delivery_status';
        $params[':delivery_status'] = $status;
        if ($photoPath !== null) {
            $sets[] = 'delivery_photo = :delivery_photo';
            $params[':delivery_photo'] = $photoPath;
        }
        if ($markPaid) {
            $sets[] = 'is_paid = 1';
        }

        $sql = "UPDATE orders SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function createOrderWithShipping($orderCode, $totalAmount, $userEmail, $receiver, $phone, $address, $created_at, $transaction_info, $shippingMethodId = 1, $shippingFee = 0)
    {
        $sql = "INSERT INTO $this->table (order_code, total_amount, user_email, receiver, phone, address, created_at, transaction_info, shipping_method_id, shipping_fee) VALUES (";
        $sql .= ":order_code, :total_amount, :user_email, :receiver, :phone, :address, :created_at, :transaction_info, :shipping_method_id, :shipping_fee)";
        $stm = $this->db->prepare($sql);
        $getLastInsertId = $stm->execute([
            'order_code' => $orderCode,
            'total_amount' => $totalAmount,
            'user_email' => $userEmail,
            'receiver' => $receiver,
            'phone' => $phone,
            'address' => $address,
            'created_at' => $created_at,
            'transaction_info' => $transaction_info,
            'shipping_method_id' => $shippingMethodId,
            'shipping_fee' => $shippingFee
        ]);
        return $this->getLastInsertId();
    }

    // Lấy lịch sử đơn hàng theo email
    public function getOrdersByEmail($email)
    {
        // ✅ Sắp xếp: đơn chưa giao ở trên, đơn trả + đã nhận ở cuối
        $sql = "SELECT * FROM $this->table WHERE user_email = ? 
                ORDER BY (CASE WHEN delivery_status IN ('da_nhan_hang', 'da_tra_hang') THEN 1 ELSE 0 END) ASC, created_at DESC";
        return $this->select($sql, [$email]);
    }
    // Lấy thông tin đơn hàng theo ID (từ bảng orders)
    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $result = $this->select($sql, [$orderId]);
        return $result ? $result[0] : null;
    }

    // Lấy danh sách sản phẩm trong đơn hàng (từ order_details)
    public function getOrderItems($orderId)
    {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    // Lấy danh sách sản phẩm kèm thông tin sản phẩm (tên, ảnh, email người bán) trong đơn hàng
    public function getOrderItemsWithProduct($orderId)
    {
        $sql = "SELECT od.*, p.tensp, p.hinhanh, p.email FROM order_details od
                LEFT JOIN tblsanpham p ON od.product_id = p.masp
                WHERE od.order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    // Lấy danh sách đơn hàng chưa giao (đã thanh toán, chưa nhận/chưa trả)
    public function getPendingOrders()
    {
        // Prefer address from orders table; if empty, fall back to address stored in order_details
        $sql = "SELECT id, order_code, user_email, receiver, phone, 
            COALESCE(NULLIF(address, ''), 
                (SELECT address FROM order_details WHERE order_id = orders.id LIMIT 1)
            ) AS address, 
            total_amount, created_at, transaction_info, delivery_status
            FROM $this->table WHERE transaction_info = 'dathanhtoan' AND (delivery_status IS NULL OR delivery_status NOT IN ('da_nhan_hang','da_tra_hang')) ORDER BY created_at DESC";
        return $this->select($sql, []);
    }

    // Lấy tất cả đơn hàng chưa giao (bất kể thanh toán hay chưa)
    public function getUndeliveredOrders()
    {
        // Get all orders that haven't been delivered yet, regardless of payment status
        // Exclude orders with delivery_status = 'da_nhan_hang', 'da_tra_hang' or 'da_huy'
        $sql = "SELECT id, order_code, user_email, receiver, phone, 
            COALESCE(NULLIF(address, ''), 
                (SELECT address FROM order_details WHERE order_id = orders.id LIMIT 1)
            ) AS address, 
            total_amount, created_at, transaction_info, delivery_status
            FROM $this->table WHERE (delivery_status IS NULL OR (delivery_status NOT IN ('da_nhan_hang','da_tra_hang','da_huy'))) ORDER BY created_at DESC";
        return $this->select($sql, []);
    }

    // ✅ Lấy chỉ các đơn hàng đã giao cho đơn vị vận chuyển (để shipper xử lý)
    public function getAssignedOrders()
    {
        $sql = "SELECT id, order_code, user_email, receiver, phone, 
            COALESCE(NULLIF(address, ''), 
                (SELECT address FROM order_details WHERE order_id = orders.id LIMIT 1)
            ) AS address, 
            total_amount, created_at, transaction_info, delivery_status
            FROM $this->table WHERE delivery_status = 'da_giao_dvvc' ORDER BY created_at DESC";
        return $this->select($sql, []);
    }

    // Cancel an order by its order code (mark transaction_info and delivery_status)
    public function cancelOrderByCode($orderCode)
    {
        $sql = "UPDATE $this->table SET transaction_info = 'huydon', delivery_status = 'da_huy' WHERE order_code = :orderCode";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':orderCode' => $orderCode]);
    }

    // Automatically cancel stale online-payment orders older than given minutes
    public function autoCancelStaleOnlineOrders($minutes = 30)
    {
        $threshold = date('Y-m-d H:i:s', time() - ((int)$minutes * 60));
        $sql = "UPDATE $this->table SET transaction_info = 'huydon', delivery_status = 'da_huy' WHERE transaction_info = 'thanhtoanvnpay' AND created_at <= :threshold";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':threshold' => $threshold]);
    }

    // Giảm số lượng sản phẩm từ tblsanpham dựa vào order_id
    public function decreaseProductQuantity($orderId)
    {
        // Get all items in the order
        $sql = "SELECT product_id, quantity FROM order_details WHERE order_id = ?";
        $orderItems = $this->select($sql, [$orderId]);
        
        foreach ($orderItems as $item) {
            $updateSql = "UPDATE tblsanpham SET soluong = soluong - ? WHERE masp = ?";
            $stmt = $this->db->prepare($updateSql);
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
        return true;
    }

    // Cộng lại số lượng sản phẩm từ tblsanpham dựa vào order_id (khi shipper trả hàng)
    public function restoreProductQuantity($orderId)
    {
        // Get all items in the order
        $sql = "SELECT product_id, quantity FROM order_details WHERE order_id = ?";
        $orderItems = $this->select($sql, [$orderId]);
        
        foreach ($orderItems as $item) {
            $updateSql = "UPDATE tblsanpham SET soluong = soluong + ? WHERE masp = ?";
            $stmt = $this->db->prepare($updateSql);
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
        return true;
    }

    // Lưu lý do trả hàng và update delivery_status
    public function markAsReturned($orderId, $returnReason)
    {
        $returnDate = date('Y-m-d H:i:s');
        $sql = "UPDATE $this->table SET delivery_status = 'da_tra_hang', return_reason = :reason, return_date = :date WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':reason' => $returnReason,
            ':date' => $returnDate,
            ':id' => $orderId
        ]);
    }

    // Lấy danh sách đơn hàng bị trả
    public function getReturnedOrders()
    {
        $sql = "SELECT id, order_code, user_email, receiver, total_amount, return_reason, return_date, created_at 
                FROM $this->table 
                WHERE delivery_status = 'da_tra_hang' 
                ORDER BY return_date DESC";
        return $this->select($sql, []);
    }

    // User hủy đơn hàng (chỉ cho phép khi đơn chưa xác nhận, chuẩn bị hàng, hoặc đã giao DVVC)
    public function cancelOrder($orderId, $cancelReason)
    {
        $cancelDate = date('Y-m-d H:i:s');
        $sql = "UPDATE $this->table SET 
                delivery_status = 'da_huy', 
                cancel_reason = :reason, 
                cancel_date = :date,
                transaction_info = 'huydon'
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':reason' => $cancelReason,
            ':date' => $cancelDate,
            ':id' => $orderId
        ]);
    }

    // Kiểm tra xem đơn hàng có thể hủy không (dựa vào delivery_status)
    public function canCancelOrder($orderId)
    {
        $order = $this->getOrderById($orderId);
        if (!$order) return false;
        
        $allowedStatuses = ['chua_xac_nhan', 'dang_chuan_bi', 'da_giao_dvvc', null];
        return in_array($order['delivery_status'], $allowedStatuses);
    }
}
