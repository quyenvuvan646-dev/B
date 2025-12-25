<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn của tôi - Distributor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .messages-page {
            padding: 2rem 0;
        }

        .page-title {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(240, 147, 251, 0.25);
        }

        .order-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-code {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .unread-badge {
            background: #fff;
            color: #f5576c;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(255, 255, 255, 0);
            }
        }

        .order-body {
            padding: 1.5rem;
        }

        .info-row {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .info-icon {
            color: #f5576c;
            font-size: 1.2rem;
            margin-top: 0.1rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #666;
            font-size: 0.95rem;
        }

        .view-chat-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        }

        .view-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
            color: white;
        }

        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 5rem;
            color: #f093fb;
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: #333;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="container messages-page">
    <h2 class="page-title">
        <i class="bi bi-chat-dots"></i> Tin nhắn của tôi
    </h2>

    <?php if (empty($data['orders'])): ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Chưa có đơn hàng nào</h4>
            <p>Bạn chưa có đơn hàng nào để nhắn tin với khách hàng.</p>
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
                                <i class="bi bi-person-circle info-icon"></i>
                                <div class="info-content">
                                    <div class="info-label">Khách hàng</div>
                                    <div class="info-value"><?= htmlspecialchars($order['receiver'] ?? 'N/A') ?></div>
                                </div>
                            </div>
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
</div>

</body>
</html>
