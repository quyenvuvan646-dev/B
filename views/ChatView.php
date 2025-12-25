<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Đơn hàng #<?= htmlspecialchars($data['order']['order_code']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .chat-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .order-info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .order-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .order-header i {
            font-size: 2rem;
        }

        .order-body {
            padding: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }

        .info-item i {
            color: #667eea;
            margin-top: 0.2rem;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            margin-right: 0.5rem;
        }

        .info-value {
            color: #666;
        }

        .product-list {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .chat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 600px;
        }

        .chat-header {
            background: var(--success-gradient);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chat-header i {
            font-size: 1.5rem;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
            scroll-behavior: smooth;
        }

        .messages-container::-webkit-scrollbar {
            width: 8px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .message-wrapper {
            display: flex;
            margin-bottom: 1.5rem;
            animation: fadeInMessage 0.3s ease;
        }

        @keyframes fadeInMessage {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-wrapper.sent {
            justify-content: flex-end;
        }

        .message-wrapper.received {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 70%;
            padding: 1rem 1.25rem;
            border-radius: 18px;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            word-wrap: break-word;
        }

        .message-wrapper.sent .message-bubble {
            background: var(--primary-gradient);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-wrapper.received .message-bubble {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .message-auto {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%) !important;
            color: #2d3436 !important;
            border: 2px solid #fdcb6e;
            text-align: center;
            max-width: 85% !important;
            margin: 1rem auto !important;
        }

        .message-auto-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .message-text {
            margin: 0;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .message-image {
            margin-top: 0.75rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .message-image img {
            max-width: 200px;
            max-height: 200px;
            display: block;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .message-image img:hover {
            transform: scale(1.05);
        }

        .message-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .message-wrapper.sent .message-meta {
            color: rgba(255, 255, 255, 0.9);
        }

        .message-wrapper.received .message-meta {
            color: #6c757d;
        }

        .empty-chat {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }

        .empty-chat i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .chat-input-area {
            padding: 1.25rem;
            background: white;
            border-top: 1px solid #e9ecef;
        }

        .chat-form {
            display: flex;
            gap: 0.75rem;
            align-items: end;
        }

        .message-input {
            flex: 1;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 0.75rem 1.25rem;
            resize: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .message-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .send-button {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.75rem 1.75rem;
            border-radius: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .send-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .send-button:active {
            transform: translateY(0);
        }

        .back-button {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .chat-card {
                height: 500px;
            }

            .message-bubble {
                max-width: 85%;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="chat-container">
    <!-- Chi tiết đơn hàng -->
    <div class="order-info-card">
        <div class="order-header">
            <i class="bi bi-box-seam"></i>
            <div>
                <h5 class="mb-0">Đơn hàng #<?= htmlspecialchars($data['order']['order_code']) ?></h5>
                <small>Đặt ngày <?= date('d/m/Y H:i', strtotime($data['order']['created_at'])) ?></small>
            </div>
        </div>
        <div class="order-body">
            <div class="info-grid">
                <div class="info-item">
                    <i class="bi bi-person-circle"></i>
                    <div>
                        <span class="info-label">Người mua:</span>
                        <span class="info-value"><?= htmlspecialchars($data['order']['user_email']) ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-person-badge"></i>
                    <div>
                        <span class="info-label">Người nhận:</span>
                        <span class="info-value"><?= htmlspecialchars($data['order']['receiver'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <span class="info-label">SĐT:</span>
                        <span class="info-value"><?= htmlspecialchars($data['order']['phone'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        <span class="info-label">Địa chỉ:</span>
                        <span class="info-value"><?= htmlspecialchars($data['order']['address'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="bi bi-cash-coin"></i>
                    <div>
                        <span class="info-label">Tổng tiền:</span>
                        <span class="info-value fw-bold" style="color: #00b894;"><?= number_format($data['order']['total_amount'],0,',','.') ?>₫</span>
                    </div>
                </div>
            </div>

            <div class="product-list">
                <h6 class="mb-3"><i class="bi bi-bag-check"></i> Sản phẩm trong đơn</h6>
                <?php foreach ($data['orderItems'] as $item): ?>
                    <div class="product-item">
                        <span><?= htmlspecialchars($item['product_name'] ?? $item['tensp'] ?? '') ?> <span class="text-muted">×<?= $item['quantity'] ?></span></span>
                        <span class="fw-bold text-primary"><?= number_format($item['total'] ?? (($item['sale_price'] ?? 0) * $item['quantity']),0,',','.') ?>₫</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Phần chat -->
    <div class="chat-card">
        <div class="chat-header">
            <i class="bi bi-chat-dots"></i>
            <h5 class="mb-0">Tin nhắn trao đổi</h5>
        </div>

        <div class="messages-container" id="messagesContainer">
            <?php if (empty($data['messages'])): ?>
                <div class="empty-chat">
                    <i class="bi bi-chat-text"></i>
                    <p>Chưa có tin nhắn nào</p>
                    <small>Hãy bắt đầu cuộc trò chuyện!</small>
                </div>
            <?php else: ?>
                <?php foreach ($data['messages'] as $msg): ?>
                    <?php if ($msg['type'] === 'auto'): ?>
                        <div class="message-wrapper">
                            <div class="message-bubble message-auto">
                                <div class="message-auto-badge">
                                    <i class="bi bi-bell-fill"></i>
                                    Thông báo đơn hàng mới
                                </div>
                                <p class="message-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                <div class="message-meta" style="justify-content: center; color: #636e72;">
                                    <i class="bi bi-clock"></i>
                                    <span><?= date('H:i - d/m/Y', strtotime($msg['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="message-wrapper <?= $msg['sender_email'] === $data['user']['email'] ? 'sent' : 'received' ?>">
                            <div class="message-bubble">
                                <p class="message-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                <?php if (!empty($msg['image_url'])): ?>
                                    <div class="message-image">
                                        <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($msg['image_url']) ?>" 
                                             alt="Attachment"
                                             onclick="window.open(this.src, '_blank')">
                                    </div>
                                <?php endif; ?>
                                <div class="message-meta">
                                    <i class="bi bi-person-circle"></i>
                                    <span><?= htmlspecialchars(explode('@', $msg['sender_email'])[0]) ?></span>
                                    <i class="bi bi-clock"></i>
                                    <span><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="chat-input-area">
            <form method="POST" action="<?= APP_URL ?>/ChatController/send" class="chat-form">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($data['order_id']) ?>">
                <input type="hidden" name="receiver_email" value="<?php 
                    $receiverEmail = '';
                    $currentUserEmail = $data['user']['email'];
                    
                    if (!empty($data['messages'])) {
                        foreach ($data['messages'] as $msg) {
                            if ($msg['sender_email'] !== $currentUserEmail) {
                                $receiverEmail = $msg['sender_email'];
                                break;
                            }
                            if ($msg['sender_email'] === $currentUserEmail && !empty($msg['receiver_email'])) {
                                $receiverEmail = $msg['receiver_email'];
                                break;
                            }
                        }
                    }
                    
                    if (empty($receiverEmail)) {
                        if ($currentUserEmail === $data['order']['user_email'] && !empty($data['orderItems'][0]['email'])) {
                            $receiverEmail = $data['orderItems'][0]['email'];
                        } else if (!empty($data['orderItems'])) {
                            foreach ($data['orderItems'] as $item) {
                                if ($item['email'] === $currentUserEmail) {
                                    $receiverEmail = $data['order']['user_email'];
                                    break;
                                }
                            }
                        }
                    }
                    
                    echo htmlspecialchars($receiverEmail);
                ?>">
                <textarea name="message" 
                          class="message-input" 
                          placeholder="Nhập tin nhắn của bạn..." 
                          rows="2"
                          required></textarea>
                <button type="submit" class="send-button">
                    <i class="bi bi-send-fill"></i>
                    Gửi
                </button>
            </form>
        </div>
    </div>

    <a href="javascript:history.back()" class="back-button">
        <i class="bi bi-arrow-left"></i>
        Quay lại
    </a>
</div>

<script>
// Auto scroll to bottom when page loads
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});

// Auto resize textarea
const textarea = document.querySelector('.message-input');
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 150) + 'px';
});

// Submit on Enter (Shift+Enter for new line)
textarea.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>

</body>
</html>

