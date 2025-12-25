<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; color: white; font-size: 28px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        🤖
                    </div>
                    <div>
                        <h2 class="mb-0">💬 AI Assistant</h2>
                        <small class="text-muted">Trợ lý ảo thông minh - Hỗ trợ 24/7</small>
                    </div>
                </div>
                <hr>
            </div>

            <!-- Chat Container -->
            <div class="card shadow-lg border-0" style="height: 600px; display: flex; flex-direction: column;">
                <!-- Chat Messages Area -->
                <div class="card-body p-4" id="chatMessages" style="flex: 1; overflow-y: auto; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                    <!-- Tin nhắn ban đầu -->
                    <div class="message bot mb-3">
                        <div class="d-flex gap-2 align-items-start">
                            <div style="font-size: 24px;">🤖</div>
                            <div class="bg-white rounded-lg p-3 shadow-sm" style="max-width: 80%;">
                                <p class="mb-0">👋 Xin chào! Tôi là AI Assistant. Bạn muốn hỏi gì? Tôi sẽ cố gắng giúp bạn.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input Area -->
                <div class="card-footer border-0 bg-white p-3" style="border-top: 1px solid #e0e0e0;">
                    <form id="chatForm" class="d-flex gap-2">
                        <input 
                            type="text" 
                            id="messageInput" 
                            class="form-control form-control-lg" 
                            placeholder="Nhập tin nhắn của bạn... (Nhấn Enter để gửi)"
                            autocomplete="off"
                            style="border-radius: 25px; border: 2px solid #e0e0e0;"
                        >
                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 50%; width: 50px; height: 50px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                    <div id="loadingIndicator" style="display: none; text-align: center; margin-top: 10px;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Đang xử lý...</span>
                        </div>
                        <small class="text-muted ms-2">AI đang suy nghĩ...</small>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-4" role="alert">
                <i class="bi bi-info-circle"></i>
                <strong>Mẹo:</strong> AI này hoạt động offline, hoàn toàn miễn phí. Bạn có thể hỏi về:
                <ul class="mb-0 mt-2">
                    <li>💡 Tư vấn, hướng dẫn</li>
                    <li>📝 Viết nội dung</li>
                    <li>🔍 Trả lời câu hỏi</li>
                    <li>📚 Học tập, thông tin</li>
                </ul>
            </div>

            <!-- Quick Questions -->
            <div class="mt-4">
                <h6 class="mb-3">❓ Câu hỏi gợi ý:</h6>
                <div class="d-grid gap-2" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                    <button type="button" class="btn btn-outline-secondary quick-question">
                        🛍️ Sản phẩm có độ bền cao không?
                    </button>
                    <button type="button" class="btn btn-outline-secondary quick-question">
                        💳 Thanh toán có an toàn?
                    </button>
                    <button type="button" class="btn btn-outline-secondary quick-question">
                        🚚 Giao hàng mất bao lâu?
                    </button>
                    <button type="button" class="btn btn-outline-secondary quick-question">
                        ❌ Đổi trả như thế nào?
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .message {
        margin-bottom: 20px;
        animation: slideIn 0.3s ease-in-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.user {
        text-align: right;
    }

    .message.user .msg-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-left: auto;
    }

    .message.bot .msg-bubble {
        background: white;
        color: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .msg-bubble {
        padding: 12px 16px;
        border-radius: 18px;
        max-width: 80%;
        word-wrap: break-word;
    }

    #chatMessages {
        scroll-behavior: smooth;
    }

    .quick-question {
        text-align: left;
        white-space: normal;
        padding: 10px 15px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .quick-question:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    @media (max-width: 768px) {
        .msg-bubble {
            max-width: 100%;
        }
        
        .card {
            height: 500px !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('chatForm');
    const input = document.getElementById('messageInput');
    const messagesDiv = document.getElementById('chatMessages');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    // Quick questions
    document.querySelectorAll('.quick-question').forEach(btn => {
        btn.addEventListener('click', function() {
            input.value = this.textContent.trim();
            sendMessage();
        });
    });

    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        // Hiển thị tin nhắn user
        addMessage(message, 'user');
        input.value = '';
        loadingIndicator.style.display = 'block';

        // Gửi đến server
        fetch('<?= APP_URL ?>/ChatBot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'message=' + encodeURIComponent(message)
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = 'none';
            if (data.success) {
                addMessage(data.reply, 'bot');
            } else {
                addMessage('❌ ' + (data.error || 'Có lỗi xảy ra'), 'bot');
            }
        })
        .catch(error => {
            loadingIndicator.style.display = 'none';
            addMessage('❌ Lỗi: ' + error.message, 'bot');
        });
    }

    function addMessage(text, sender) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'message ' + sender;
        
        if (sender === 'user') {
            msgDiv.innerHTML = `
                <div class="d-flex gap-2 align-items-start justify-content-end">
                    <div class="msg-bubble">${escapeHtml(text)}</div>
                    <div style="font-size: 24px;">👤</div>
                </div>
            `;
        } else {
            msgDiv.innerHTML = `
                <div class="d-flex gap-2 align-items-start">
                    <div style="font-size: 24px;">🤖</div>
                    <div class="msg-bubble">${escapeHtml(text).replace(/\n/g, '<br>')}</div>
                </div>
            `;
        }
        
        messagesDiv.appendChild(msgDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});
</script>
