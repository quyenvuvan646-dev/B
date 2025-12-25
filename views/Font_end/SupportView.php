<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 10px;">
                    <i class="bi bi-chat-dots"></i> Trung Tâm Hỗ Trợ
                    <?php if (isset($_SESSION['user'])): ?>
                    <span class="badge bg-danger rounded-pill" id="supportPageResponseBadge" style="font-size: 0.7rem; margin-left: 10px; display: none;">0</span>
                    <?php endif; ?>
                </h1>
                <p style="font-size: 1.1rem; color: #666;">
                    Chúng tôi luôn sẵn sàng giúp bạn
                </p>
                <?php if (isset($_SESSION['user'])): ?>
                <p id="supportPageNotificationText" style="display: none; color: #dc2626; font-weight: 500; margin-top: 10px;">
                    <i class="bi bi-bell-fill"></i> <span id="supportPageNotificationCount">0</span> phản hồi mới từ admin
                </p>
                <div style="margin-top: 15px;">
                    <a href="<?php echo APP_URL; ?>/Support/myTickets" class="btn btn-outline-primary">
                        <i class="bi bi-clock-history"></i> Xem Lịch Sử Hỗ Trợ
                        <span class="badge bg-danger rounded-pill ms-2" id="historyBadge" style="display: none;">0</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Support Options -->
            <div class="row mb-4">
                <!-- AI Chat Option -->
                <div class="col-md-6 mb-3">
                    <div class="card h-100" style="border: 2px solid #3b82f6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);">
                        <div class="card-body text-center">
                            <div style="font-size: 3rem; margin-bottom: 15px;">
                                <i class="bi bi-robot" style="color: #3b82f6;"></i>
                            </div>
                            <h5 class="card-title">Chat với AI</h5>
                            <p class="card-text">Nhận câu trả lời tức thì từ chatbot AI</p>
                            <button class="btn btn-primary" onclick="showAIChat()" style="width: 100%;">
                                <i class="bi bi-chat-fill"></i> Bắt Đầu Chat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Admin Contact Option -->
                <div class="col-md-6 mb-3">
                    <div class="card h-100" style="border: 2px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
                        <div class="card-body text-center">
                            <div style="font-size: 3rem; margin-bottom: 15px;">
                                <i class="bi bi-person-check" style="color: #10b981;"></i>
                            </div>
                            <h5 class="card-title">Hỏi Nhà Phát Triển</h5>
                            <p class="card-text">Chat trực tiếp với admin để được hỗ trợ</p>
                            <button class="btn btn-success" onclick="showAdminChat()" style="width: 100%;">
                                <i class="bi bi-chat-left-text-fill"></i> Liên Hệ Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Chat Section -->
            <div id="aiChatSection" style="display: none; margin-top: 30px;">
                <div class="card" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 style="margin: 0;">
                                <i class="bi bi-robot"></i> Chat với AI Chatbot
                            </h5>
                            <button type="button" class="btn-close btn-close-white" onclick="hideAIChat()"></button>
                        </div>
                    </div>
                    <div class="card-body" style="height: 400px; overflow-y: auto; background-color: #f8fafc;" id="aiChatMessages">
                        <!-- Messages will appear here -->
                        <div style="text-align: center; padding: 20px; color: #999;">
                            <p>Xin chào! 👋 Bạn có câu hỏi nào không?</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form onsubmit="sendAIMessage(event)" style="display: flex; gap: 10px;">
                            <input 
                                type="text" 
                                id="aiInput" 
                                class="form-control" 
                                placeholder="Nhập câu hỏi..."
                                required
                            >
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill"></i> Gửi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Admin Chat Section -->
            <div id="adminChatSection" style="display: none; margin-top: 30px;">
                <div class="card" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                    <div class="card-header bg-success text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h5 style="margin: 0;">
                                    <i class="bi bi-person-check"></i> Chat với Admin
                                </h5>
                                <small style="opacity: 0.9;">Xem lịch sử & gửi tin nhắn mới</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" onclick="hideAdminChat()"></button>
                        </div>
                    </div>
                    <div class="card-body" style="height: 450px; overflow-y: auto; background-color: #f8fafc;" id="adminChatMessages">
                        <!-- Messages will appear here -->
                        <div style="text-align: center; padding: 20px; color: #999;">
                            <p>Gửi tin nhắn cho admin để được hỗ trợ sớm nhất</p>
                        </div>
                    </div>
                    <div class="card-footer" style="background-color: #f8fafc;">
                        <div style="margin-bottom: 8px; font-size: 0.85rem; color: #64748b;">
                            <i class="bi bi-info-circle"></i> Tin nhắn của bạn sẽ được gửi đến admin và lưu vào lịch sử
                        </div>
                        <form onsubmit="sendAdminMessage(event)" style="display: flex; gap: 10px;">
                            <input 
                                type="text" 
                                id="adminInput" 
                                class="form-control" 
                                placeholder="Nhập tin nhắn..."
                                required
                            >
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send-fill"></i> Gửi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div style="margin-top: 50px;">
                <h3 style="text-align: center; margin-bottom: 30px;">
                    <i class="bi bi-question-circle"></i> Câu Hỏi Thường Gặp
                </h3>

                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Làm sao để đặt hàng?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Bạn có thể dễ dàng đặt hàng bằng cách: Duyệt sản phẩm → Chọn → Thêm vào giỏ → Thanh toán. Chúng tôi hỗ trợ thanh toán VNPay và COD.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Thời gian giao hàng là bao lâu?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                • Hà Nội, TP.HCM: 1-2 ngày<br>
                                • Các tỉnh khác: 2-5 ngày<br>
                                Bạn có thể theo dõi đơn hàng trong "Lịch sử đơn hàng"
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Chính sách hoàn trả như thế nào?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                • Hoàn trả trong 7 ngày nếu sản phẩm lỗi<br>
                                • Hoàn tiền 100% nếu không hài lòng<br>
                                • Liên hệ hỗ trợ để được giúp đỡ
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Làm sao để trở thành nhà phân phối?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                1. Đăng nhập tài khoản<br>
                                2. Nhấn "Đăng Kí Kinh Doanh" trên trang chủ<br>
                                3. Điền thông tin công ty<br>
                                4. Chờ admin duyệt (1-3 ngày)<br>
                                5. Bạn sẽ có quyền phân phối
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div style="margin-top: 50px; padding: 30px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 10px; text-align: center;">
                <h4 style="margin-bottom: 20px;">
                    <i class="bi bi-telephone"></i> Thông Tin Liên Hệ
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <p style="color: #999; margin-bottom: 5px;">📧 Email</p>
                        <p><strong>support@example.com</strong></p>
                    </div>
                    <div>
                        <p style="color: #999; margin-bottom: 5px;">📱 Hotline</p>
                        <p><strong>1900 1234</strong></p>
                    </div>
                    <div>
                        <p style="color: #999; margin-bottom: 5px;">⏰ Giờ Làm Việc</p>
                        <p><strong>8:00 - 18:00 (Thứ 2-6)</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    #aiChatMessages, #adminChatMessages {
        display: flex;
        flex-direction: column;
    }

    .message {
        margin-bottom: 12px;
        padding: 10px 15px;
        border-radius: 8px;
        max-width: 80%;
        word-wrap: break-word;
    }

    .message.user {
        align-self: flex-end;
        background-color: #3b82f6;
        color: white;
    }

    .message.ai {
        align-self: flex-start;
        background-color: #e0e7ff;
        color: #1f2937;
    }

    .message.admin {
        align-self: flex-start;
        background-color: #dcfce7;
        color: #1f2937;
    }

    .message.system {
        align-self: center;
        background-color: #fef3c7;
        color: #78350f;
        font-size: 0.9rem;
        text-align: center;
        max-width: 80%;
    }

    .message.timestamp {
        font-size: 0.75rem;
        color: #999;
        margin-top: 3px;
    }
</style>

<script>
function showAIChat() {
    document.getElementById('aiChatSection').style.display = 'block';
    document.getElementById('adminChatSection').style.display = 'none';
    document.getElementById('aiInput').focus();
}

function hideAIChat() {
    document.getElementById('aiChatSection').style.display = 'none';
}

function showAdminChat() {
    <?php if (!isset($_SESSION['user'])): ?>
        alert('Vui lòng đăng nhập để liên hệ với admin');
        window.location.href = '<?php echo APP_URL; ?>/AuthController/ShowLogin';
        return;
    <?php endif; ?>
    
    document.getElementById('adminChatSection').style.display = 'block';
    document.getElementById('aiChatSection').style.display = 'none';
    
    // Load chat history
    loadChatHistory();
    
    // Start auto-refresh for new messages
    startAdminChatRefresh();
    
    document.getElementById('adminInput').focus();
}

function hideAdminChat() {
    document.getElementById('adminChatSection').style.display = 'none';
    
    // Stop auto-refresh
    stopAdminChatRefresh();
}

let adminChatRefreshInterval = null;
let lastMessageCount = 0;

function startAdminChatRefresh() {
    // Clear any existing interval
    stopAdminChatRefresh();
    
    // Refresh every 10 seconds
    adminChatRefreshInterval = setInterval(() => {
        refreshChatHistory();
    }, 10000);
}

function stopAdminChatRefresh() {
    if (adminChatRefreshInterval) {
        clearInterval(adminChatRefreshInterval);
        adminChatRefreshInterval = null;
    }
}

function refreshChatHistory() {
    fetch('<?php echo APP_URL; ?>/?url=Support/getChatHistory')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages.length > lastMessageCount) {
                // Only refresh if there are new messages
                const messagesDiv = document.getElementById('adminChatMessages');
                const isScrolledToBottom = messagesDiv.scrollHeight - messagesDiv.clientHeight <= messagesDiv.scrollTop + 1;
                
                loadChatHistory();
                
                // Keep scroll position if not at bottom, otherwise scroll to bottom
                if (!isScrolledToBottom) {
                    // User is reading old messages, don't auto-scroll
                } else {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
                
                lastMessageCount = data.messages.length;
            }
        })
        .catch(err => console.error('Error refreshing chat:', err));
}

function loadChatHistory() {
    const messagesDiv = document.getElementById('adminChatMessages');
    const isFirstLoad = messagesDiv.innerHTML.includes('Đang tải lịch sử') || messagesDiv.innerHTML.includes('Chưa có tin nhắn');
    
    if (isFirstLoad) {
        messagesDiv.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="bi bi-hourglass-split"></i> Đang tải lịch sử...</div>';
    }
    
    fetch('<?php echo APP_URL; ?>/?url=Support/getChatHistory')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                messagesDiv.innerHTML = '';
                data.messages.forEach(msg => {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'message ' + msg.type;
                    
                    let content = msg.message;
                    if (msg.is_unread && msg.type === 'admin') {
                        content = '<span class="badge bg-danger me-2">MỚI</span>' + content;
                    }
                    
                    msgDiv.innerHTML = content + '<div class="message timestamp">' + msg.timestamp + '</div>';
                    messagesDiv.appendChild(msgDiv);
                });
                
                // Update message count
                lastMessageCount = data.messages.length;
                
                // Auto scroll to bottom to show newest messages
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                
                // Refresh badge count after loading history
                <?php if (isset($_SESSION['user'])): ?>
                if (typeof loadSupportPageResponseCount === 'function') {
                    setTimeout(loadSupportPageResponseCount, 1000);
                }
                <?php endif; ?>
            } else {
                messagesDiv.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;"><p>Chưa có tin nhắn nào. Gửi tin nhắn đầu tiên của bạn!</p></div>';
                lastMessageCount = 0;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            messagesDiv.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;"><p>Gửi tin nhắn cho admin để được hỗ trợ sớm nhất</p></div>';
        });
}

function sendAIMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('aiInput');
    const message = input.value.trim();
    const messagesDiv = document.getElementById('aiChatMessages');
    
    if (!message) return;
    
    // Add user message to chat
    const userMsg = document.createElement('div');
    userMsg.className = 'message user';
    userMsg.innerHTML = message + '<div class="message timestamp">' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) + '</div>';
    messagesDiv.appendChild(userMsg);
    
    // Clear input
    input.value = '';
    
    // Scroll to bottom
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    // Send to server
    const formData = new FormData();
    formData.append('message', message);
    
    fetch('<?php echo APP_URL; ?>/?url=Support/askAI', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.response) {
            const aiMsg = document.createElement('div');
            aiMsg.className = 'message ai';
            aiMsg.innerHTML = data.response + '<div class="message timestamp">' + data.timestamp + '</div>';
            messagesDiv.appendChild(aiMsg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    })
    .catch(err => console.error('Error:', err));
}

function sendAdminMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('adminInput');
    const message = input.value.trim();
    const messagesDiv = document.getElementById('adminChatMessages');
    
    if (!message) return;
    
    // Add user message to chat (append to existing history)
    const userMsg = document.createElement('div');
    userMsg.className = 'message user';
    userMsg.innerHTML = message + '<div class="message timestamp">' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) + '</div>';
    messagesDiv.appendChild(userMsg);
    
    // Clear input
    input.value = '';
    
    // Scroll to bottom
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    // Send to server
    const formData = new FormData();
    formData.append('message', message);
    
    const apiUrl = '<?php echo APP_URL; ?>/?url=Support/sendToAdmin';
    console.log('Sending to URL:', apiUrl);
    
    fetch(apiUrl, {
        method: 'POST',
        body: formData
    })
    .then(res => {
        console.log('Response status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message in chat
            const confirmMsg = document.createElement('div');
            confirmMsg.className = 'message system';
            confirmMsg.innerHTML = '✅ Tin nhắn đã được gửi đến admin. Họ sẽ phản hồi sớm nhất có thể.<div class="message timestamp">' + data.timestamp + '</div>';
            messagesDiv.appendChild(confirmMsg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } else if (data.error) {
            console.error('Server error:', data.error);
            const errorMsg = document.createElement('div');
            errorMsg.className = 'message system';
            errorMsg.style.backgroundColor = '#fee2e2';
            errorMsg.style.color = '#7f1d1d';
            errorMsg.innerHTML = '❌ Lỗi: ' + data.error + '<div class="message timestamp">' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) + '</div>';
            messagesDiv.appendChild(errorMsg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        const errorMsg = document.createElement('div');
        errorMsg.className = 'message system';
        errorMsg.style.backgroundColor = '#fee2e2';
        errorMsg.style.color = '#7f1d1d';
        errorMsg.innerHTML = '❌ Có lỗi xảy ra khi gửi tin nhắn: ' + err.message + '<div class="message timestamp">' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) + '</div>';
        messagesDiv.appendChild(errorMsg);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}

<?php if (isset($_SESSION['user'])): ?>
// Auto-refresh support response count for badge
function loadSupportPageResponseCount() {
    fetch('<?php echo APP_URL; ?>/?url=Support/getUnreadResponseCount')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('supportPageResponseBadge');
            const notificationText = document.getElementById('supportPageNotificationText');
            const notificationCount = document.getElementById('supportPageNotificationCount');
            const historyBadge = document.getElementById('historyBadge');
            
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline-block';
                notificationText.style.display = 'block';
                notificationCount.textContent = data.count;
                historyBadge.textContent = data.count;
                historyBadge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
                notificationText.style.display = 'none';
                historyBadge.style.display = 'none';
            }
        })
        .catch(err => console.error('Error loading support response count:', err));
}

// Load on page load
loadSupportPageResponseCount();

// Auto-refresh every 30 seconds
setInterval(loadSupportPageResponseCount, 30000);
<?php endif; ?>
</script>
