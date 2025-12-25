<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%);
            min-height: 100vh;
            padding: 2rem 0;
            margin: 0;
        }

        .messages-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            border: 1px solid rgba(147, 197, 253, 0.2);
        }

        .page-header h2 {
            color: #93c5fd;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 10px rgba(147, 197, 253, 0.3);
        }

        .page-header h2 i {
            color: #60a5fa;
        }

        .order-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            border: 1px solid rgba(147, 197, 253, 0.2);
            height: 100%;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(96, 165, 250, 0.4);
            border-color: rgba(147, 197, 253, 0.4);
        }

        .order-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-code {
            font-weight: 600;
            font-size: 1.1rem;
            color: #e0f2fe;
        }

        .unread-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            animation: pulse 2s infinite;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }
        }

        .order-body {
            padding: 1.5rem;
            background: rgba(15, 23, 42, 0.5);
        }

        .info-row {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(71, 85, 105, 0.5);
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .info-icon {
            color: #60a5fa;
            font-size: 1.2rem;
            margin-top: 0.1rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #93c5fd;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #e2e8f0;
            font-size: 0.95rem;
        }

        .view-chat-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .view-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
            color: white;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .empty-state {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(147, 197, 253, 0.2);
        }

        .empty-state i {
            font-size: 5rem;
            color: #60a5fa;
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: #93c5fd;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .back-btn {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(147, 197, 253, 0.3);
            color: #93c5fd;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }

        .back-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(147, 197, 253, 0.3);
            color: #60a5fa;
            border-color: rgba(147, 197, 253, 0.5);
        }
    </style>
</head>
<body>

<div class="messages-container">
    <div class="page-header">
        <h2>
            <i class="bi bi-chat-dots"></i> Tin nhắn của tôi
        </h2>
    </div>

    <?php if (empty($data['orders'])): ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Chưa có đơn hàng nào</h4>
            <p>Bạn chưa có đơn hàng nào để nhắn tin. Hãy mua sắm ngay!</p>
            <a href="<?= APP_URL ?>/Home/products" class="view-chat-btn">
                <i class="bi bi-shop"></i> Đi mua sắm
            </a>
        </div>
    <?php else: ?>
        <?php
        // Get unread counts for all orders
        require_once __DIR__ . '/../../models/MessageModel.php';
        $msgModel = new MessageModel();
        $unreadCounts = [];
        foreach ($msgModel->getOrdersWithUnreadCount($_SESSION['user']['email']) as $item) {
            $unreadCounts[$item['order_id']] = $item['unread_count'];
        }
        ?>
        
        <div class="row g-4">
            <?php foreach ($data['orders'] as $order): ?>
                <?php $unreadCount = $unreadCounts[$order['id']] ?? 0; ?>
                <div class="col-md-6 col-lg-4">
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-code">
                                <i class="bi bi-receipt"></i> <?= htmlspecialchars($order['order_code']) ?>
                            </span>
                            <?php if ($unreadCount > 0): ?>
                                <span class="unread-badge">
                                    <i class="bi bi-bell-fill"></i> <?= $unreadCount ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="order-body">
                            <div class="info-row">
                                <i class="bi bi-calendar-event info-icon"></i>
                                <div class="info-content">
                                    <div class="info-label">Ngày đặt</div>
                                    <div class="info-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <i class="bi bi-cash-coin info-icon"></i>
                                <div class="info-content">
                                    <div class="info-label">Tổng tiền</div>
                                    <div class="info-value fw-bold" style="color: #00b894;">
                                        <?= number_format($order['total_amount'], 0, ',', '.') ?>₫
                                    </div>
                                </div>
                            </div>
                            <a href="<?= APP_URL ?>/ChatController/show/<?= $order['id'] ?>" class="view-chat-btn">
                                <i class="bi bi-chat-text"></i>
                                <?= $unreadCount > 0 ? 'Xem tin nhắn mới' : 'Xem tin nhắn' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="javascript:history.back()" class="back-btn">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

</body>
</html>
