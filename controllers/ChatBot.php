<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Khởi động session ngay từ đầu
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

class ChatBot extends Controller {
    
    // 🤖 Hiển thị trang chat
    public function show() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        
        $this->view('homePage', [
            'page' => 'ChatBotView'
        ]);
    }
    
    // 💬 Gửi tin nhắn và nhận phản hồi từ AI
    public function send() {
        // Khởi động session nếu chưa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        
        // Clear output buffer
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Bắt đầu capture output
        ob_start();
        
        // Set header JSON
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        
        try {
            // Kiểm tra user đăng nhập
            if (!isset($_SESSION['user'])) {
                http_response_code(401);
                $response = ['error' => 'Bạn cần đăng nhập'];
                $output = ob_get_clean();
                echo json_encode($response);
                exit;
            }
            
            // Lấy message
            $message = trim($_POST['message'] ?? '');
            if (empty($message)) {
                $response = ['error' => 'Không có tin nhắn'];
                $output = ob_get_clean();
                echo json_encode($response);
                exit;
            }
            
            // Validate message
            if (strlen($message) > 500) {
                $message = substr($message, 0, 500);
            }
            
            // Gọi FAQ engine
            $reply = $this->callOllama($message);
            
            // Lưu log (optional)
            @$this->saveChatMessage($_SESSION['user']['email'], $message, $reply);
            
            // Trả response
            $output = ob_get_clean();
            if (!empty($output)) {
                // Nếu có output, log nó
                error_log("ChatBot output: " . $output);
            }
            
            echo json_encode([
                'success' => true,
                'reply' => $reply,
                'timestamp' => date('H:i:s')
            ]);
            
        } catch (Throwable $e) {
            $output = ob_get_clean();
            error_log("ChatBot error: " . $e->getMessage());
            echo json_encode([
                'error' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    // 🔌 Chatbot dùng Template/FAQ - Không cần API
    private function callOllama($message) {
        // Chuẩn bị tin nhắn
        $message = strtolower(trim($message));
        
        // FAQ Database - Thêm Q&A của bạn vào đây
        $faqData = [
            // Về sản phẩm
            ['keywords' => ['sản phẩm', 'hàng hóa', 'mặt hàng'], 'reply' => '📦 Chúng tôi bán hàng chất lượng với giá cạnh tranh. Bạn muốn tìm sản phẩm gì? Tôi có thể giúp bạn tìm kiếm.'],
            
            // Về giao hàng
            ['keywords' => ['giao hàng', 'giao', 'vận chuyển', 'delivery', 'shipping'], 'reply' => '🚚 Chúng tôi giao hàng nhanh:\n- 1-2 ngày trong thành phố\n- 2-5 ngày các tỉnh khác\nPhí giao hàng tùy theo khoảng cách.'],
            
            // Về thanh toán
            ['keywords' => ['thanh toán', 'payment', 'tiền', 'trả tiền'], 'reply' => '💳 Chúng tôi hỗ trợ:\n- Thanh toán khi nhận hàng (COD)\n- Chuyển khoản ngân hàng\n- Ví điện tử (Momo, ZaloPay)\nTất cả đều an toàn và bảo mật.'],
            
            // Về đổi trả
            ['keywords' => ['đổi', 'trả', 'hoàn', 'hư hỏng'], 'reply' => '↩️ Chính sách đổi trả:\n- 7 ngày đổi trả miễn phí\n- Hàng phải còn nguyên vẹn\n- Liên hệ với chúng tôi để xử lý'],
            
            // Về khuyến mãi
            ['keywords' => ['khuyến mãi', 'giảm giá', 'discount', 'sale', 'coupon'], 'reply' => '🎁 Chúng tôi thường xuyên có:\n- Khuyến mãi hàng tuần\n- Mã voucher giảm giá\n- Chương trình loyalty\nHãy theo dõi website để cập nhật!'],
            
            // Về hỗ trợ khách hàng
            ['keywords' => ['hỗ trợ', 'help', 'support', 'liên hệ', 'contact'], 'reply' => '📞 Liên hệ với chúng tôi:\n- Email: support@example.com\n- Hotline: 1900-xxx-xxx\n- Chat: Bạn đang sử dụng nó ngay bây giờ!\nChúng tôi sẵn sàng giúp 24/7.'],
            
            // Về tài khoản
            ['keywords' => ['tài khoản', 'account', 'đăng nhập', 'đăng ký'], 'reply' => '👤 Quản lý tài khoản:\n- Cập nhật thông tin cá nhân\n- Xem lịch sử đơn hàng\n- Quản lý địa chỉ giao hàng\nVào menu "Thông tin cá nhân" để cập nhật.'],
            
            // Về yêu thích
            ['keywords' => ['yêu thích', 'favorite', 'lưu', 'wishlist'], 'reply' => '❤️ Bạn có thể:\n- Thêm sản phẩm vào yêu thích\n- Nhận thông báo khi giá giảm\n- Chia sẻ danh sách với bạn bè\nClick biểu tượng ❤️ để thêm!'],
            
            // Lời chào
            ['keywords' => ['xin chào', 'hello', 'hi', 'chào'], 'reply' => '👋 Xin chào bạn! Tôi là AI Assistant của cửa hàng. Tôi có thể giúp bạn với bất kỳ câu hỏi nào về sản phẩm, giao hàng, thanh toán, v.v. Bạn muốn hỏi gì?'],
            
            // Cảm ơn
            ['keywords' => ['cảm ơn', 'thanks', 'thank you'], 'reply' => '😊 Vui lòng giúp chúng tôi phục vụ bạn tốt hơn. Nếu có thêm câu hỏi, cứ hỏi tôi nhé!'],
            
            // Tạm biệt
            ['keywords' => ['tạm biệt', 'bye', 'see you'], 'reply' => '👋 Hẹn gặp lại bạn! Chúc bạn một ngày tuyệt vời và mong được phục vụ bạn lần nữa!'],


        ];
        
        // Tìm câu trả lời phù hợp
        foreach ($faqData as $faq) {
            foreach ($faq['keywords'] as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $faq['reply'];
                }
            }
        }
        
        // Nếu không tìm thấy, trả lời mặc định
        $defaultReplies = [
            '🤔 Tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi về: sản phẩm, giao hàng, thanh toán, đổi trả, hoặc liên hệ hỗ trợ?',
            '❓ Câu hỏi hay! Nhưng tôi chưa có thông tin chi tiết. Vui lòng liên hệ: nam052004@gmail.com',
            '💡 Bạn có thể thử hỏi: "Giao hàng mất bao lâu?" hoặc "Thanh toán như thế nào?"',
            '📞 Nếu bạn cần trợ giúp ngay, hãy liên hệ hotline: 0379932430'
        ];
        
        return $defaultReplies[array_rand($defaultReplies)];
    }
    
    // 💾 Lưu lịch chat vào database (tạm bỏ qua)
    private function saveChatMessage($userEmail, $userMessage, $aiResponse) {
        // TODO: Implement sau - bây giờ bỏ qua
        // try {
        //     $model = $this->model('ChatMessageModel');
        //     $model->insert($userEmail, $userMessage, $aiResponse);
        // } catch (Exception $e) {
        //     // Không lưu thất bại cũng được
        // }
    }
    
    // 📜 Lấy lịch chat (optional)
    public function history() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Bạn cần đăng nhập']);
            exit;
        }
        
        try {
            $model = $this->model('ChatMessageModel');
            $messages = $model->getByEmail($_SESSION['user']['email'], 20);
            echo json_encode(['messages' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['messages' => []]);
        }
        exit;
    }
}
?>
