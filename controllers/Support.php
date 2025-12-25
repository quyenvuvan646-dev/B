<?php
class Support extends Controller {
    
    // Show support page with AI chat and admin contact options
    public function Show() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        $user = $_SESSION['user'] ?? null;
        
        $this->view('homePage', [
            'page' => 'SupportView',
            'user' => $user
        ]);
    }
    
    // AI Chatbot response
    public function askAI() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request']);
            exit();
        }
        
        $question = $_POST['question'] ?? trim($_POST['message'] ?? '');
        
        if (!$question) {
            echo json_encode(['error' => 'Câu hỏi không được để trống']);
            exit();
        }
        
        // Get AI response
        $response = $this->getAIResponse($question);
        
        echo json_encode([
            'success' => true,
            'response' => $response,
            'timestamp' => date('H:i')
        ]);
        exit();
    }
    
    // Get AI chatbot response based on keywords
    private function getAIResponse($question) {
        $question = strtolower($question);
        
        // FAQ Responses
        $responses = [
            // Order Questions
            'đặt hàng|order|mua|giá|bao nhiêu' => 'Bạn có thể dễ dàng đặt hàng bằng cách:
1. Duyệt các sản phẩm trên trang chủ
2. Chọn sản phẩm và số lượng
3. Thêm vào giỏ hàng
4. Thanh toán qua VNPay hoặc COD
Cần giúp gì thêm?',
            
            'thanh toán|payment|vnpay|cod|tiền' => 'Chúng tôi hỗ trợ 2 phương thức thanh toán:
• VNPay: Thanh toán trực tuyến an toàn
• COD: Thanh toán khi nhận hàng
Bạn có câu hỏi nào khác không?',
            
            'giao hàng|ship|vận chuyển|delivery|tracking' => 'Thời gian giao hàng:
• Hà Nội, TP.HCM: 1-2 ngày
• Các tỉnh khác: 2-5 ngày
• Bạn có thể theo dõi đơn hàng trong "Lịch sử đơn hàng"
Cần thêm thông tin?',
            
            'trả lại|hoàn|refund|return|lỗi' => 'Chính sách hoàn trả:
• Hoàn trả trong 7 ngày nếu sản phẩm lỗi
• Hoàn tiền 100% nếu không hài lòng
• Liên hệ hỗ trợ để được hỗ trợ
Cách liên hệ: Hỏi nhà phát triển để chat với admin',
            
            // Account Questions
            'tài khoản|account|đăng nhập|login|đăng ký|register' => 'Về tài khoản:
• Đăng ký: Nhập email và mật khẩu
• Đăng nhập: Sử dụng email/mật khẩu đã đăng ký
• Quên mật khẩu: Liên hệ hỗ trợ
Bạn cần giúp gì?',
            
            // Distributor Questions
            'phân phối|distributor|kinh doanh|đăng ký distribute' => 'Để trở thành nhà phân phối:
1. Đăng nhập tài khoản
2. Nhấn "Đăng Kí Kinh Doanh" trên trang chủ
3. Điền thông tin công ty
4. Chờ admin duyệt (1-3 ngày)
5. Sau khi duyệt, bạn sẽ có quyền phân phối
Có câu hỏi nào khác?',
            
            'mã giảm giá|voucher|mã code|discount' => 'Về voucher và mã giảm giá:
• Có 2 loại: Giảm giá gốc và giảm phí ship
• Xem mã khuyến mãi tại mục "Khuyến Mãi"
• Nhập mã khi thanh toán để áp dụng
Cần giúp gì?',
            
            // Default responses
            'cảm ơn|thank|gracias|merci' => 'Không có gì! Chúc bạn mua sắm vui vẻ! 😊',
            
            'xin chào|hello|hi|chào|lò' => 'Xin chào! 👋 
Tôi là chatbot hỗ trợ khách hàng. Bạn có câu hỏi nào về:
• Đặt hàng & thanh toán
• Giao hàng & theo dõi
• Hoàn trả hàng
• Tài khoản
• Phân phối
Hãy hỏi tôi!',
        ];
        
        // Check keywords
        foreach ($responses as $keywords => $response) {
            $keywordArray = array_map('trim', explode('|', $keywords));
            foreach ($keywordArray as $keyword) {
                if (strpos($question, $keyword) !== false) {
                    return $response;
                }
            }
        }
        
        // Default response
        return 'Câu hỏi hay! 😊
Tôi chưa có câu trả lời cụ thể cho vấn đề này. Vui lòng:
• Chọn "Hỏi nhà phát triển" để chat với admin
• Hoặc liên hệ email: support@example.com
Chúc bạn tìm được câu trả lời!';
    }
    
    // Send message to admin
    public function sendToAdmin() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request']);
            exit();
        }
        
        $user = $_SESSION['user'] ?? null;
        
        if (!$user) {
            echo json_encode(['error' => 'Vui lòng đăng nhập để liên hệ']);
            exit();
        }
        
        $message = $_POST['message'] ?? '';
        
        if (empty($message)) {
            echo json_encode(['error' => 'Tin nhắn không được để trống']);
            exit();
        }
        
        // Save message to database
        try {
            $supportModel = $this->model('SupportTicketModel');
            
            $userEmail = $user['email'] ?? '';
            if (empty($userEmail)) {
                echo json_encode(['error' => 'Email người dùng không hợp lệ']);
                exit();
            }
            
            $ticketId = $supportModel->createTicket(
                $userEmail,
                $message,
                'open'
            );
            
            if ($ticketId !== false && $ticketId > 0) {
                // Create notification for admin (non-critical, don't fail if error)
                try {
                    $notificationModel = $this->model('NotificationModel');
                    $userName = $user['fullname'] ?? $user['name'] ?? $userEmail;
                    $notificationModel->createNotification(
                        'support_ticket',
                        'Tin nhắn hỗ trợ mới từ ' . $userName,
                        $userEmail . ': ' . substr($message, 0, 100) . (strlen($message) > 100 ? '...' : ''),
                        $ticketId,
                        'support_ticket'
                    );
                } catch (Exception $e) {
                    // Notification error won't stop ticket creation
                    error_log('Notification creation failed: ' . $e->getMessage());
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Tin nhắn đã được gửi. Admin sẽ phản hồi sớm!',
                    'ticketId' => $ticketId,
                    'timestamp' => date('H:i')
                ]);
            } else {
                echo json_encode(['error' => 'Lỗi khi gửi tin nhắn. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            error_log('Support ticket creation error: ' . $e->getMessage());
            echo json_encode(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit();
    }
    
    // Get support tickets for a user
    public function myTickets() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        
        $user = $_SESSION['user'];
        $supportModel = $this->model('SupportTicketModel');
        $tickets = $supportModel->getTicketsByEmail($user['email']);
        
        $this->view('homePage', [
            'page' => 'SupportTicketsView',
            'tickets' => $tickets,
            'user' => $user
        ]);
    }
    
    // Get count of tickets with admin response (for badge)
    public function getUnreadResponseCount() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            exit();
        }
        
        $user = $_SESSION['user'];
        $supportModel = $this->model('SupportTicketModel');
        
        // Use new method to get accurate unread count
        $unreadCount = $supportModel->getUnreadResponseCount($user['email']);
        
        echo json_encode(['count' => $unreadCount]);
        exit();
    }
    
    // View ticket detail and mark as read
    public function viewTicket($id) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        
        $user = $_SESSION['user'];
        $supportModel = $this->model('SupportTicketModel');
        $ticket = $supportModel->getTicketById($id);
        
        // Verify ticket belongs to user
        if (!$ticket || $ticket['user_email'] !== $user['email']) {
            header('Location: ' . APP_URL . '/Support/myTickets');
            exit();
        }
        
        // Mark as read if has admin response and not yet read
        if (!empty($ticket['admin_response']) && $ticket['user_read_at'] === null) {
            $supportModel->markAsReadByUser($id);
            $ticket['user_read_at'] = date('Y-m-d H:i:s'); // Update for display
        }
        
        $this->view('homePage', [
            'page' => 'UserTicketDetailView',
            'ticket' => $ticket,
            'user' => $user
        ]);
    }
    
    // Get chat history for user (for admin chat interface)
    public function getChatHistory() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Vui lòng đăng nhập']);
            exit();
        }
        
        $user = $_SESSION['user'];
        $supportModel = $this->model('SupportTicketModel');
        
        // Get tickets ordered by time (oldest first, newest last)
        $sql = "SELECT * FROM support_tickets 
                WHERE user_email = ? 
                ORDER BY created_at ASC";
        $tickets = $supportModel->select($sql, [$user['email']]);
        
        // Format for chat display
        $messages = [];
        foreach ($tickets as $ticket) {
            // User message
            $messages[] = [
                'type' => 'user',
                'message' => nl2br(htmlspecialchars($ticket['message'])),
                'timestamp' => date('d/m H:i', strtotime($ticket['created_at'])),
                'ticket_id' => $ticket['id'],
                'is_unread' => false
            ];
            
            // Admin response (if exists)
            if (!empty($ticket['admin_response'])) {
                $isUnread = empty($ticket['user_read_at']);
                $messages[] = [
                    'type' => 'admin',
                    'message' => nl2br(htmlspecialchars($ticket['admin_response'])),
                    'timestamp' => date('d/m H:i', strtotime($ticket['updated_at'])),
                    'ticket_id' => $ticket['id'],
                    'is_unread' => $isUnread
                ];
                
                // Mark as read if unread
                if ($isUnread) {
                    $supportModel->markAsReadByUser($ticket['id']);
                }
            }
        }
        
        echo json_encode(['success' => true, 'messages' => $messages]);
        exit();
    }
}
