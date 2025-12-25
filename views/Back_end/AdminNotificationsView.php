<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">🔔 Thông báo</h2>
        </div>
        <div class="col-md-6 text-end">
            <?php if ($unreadCount > 0): ?>
                <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                    Đánh dấu tất cả đã đọc
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notification Count -->
    <div class="alert alert-info mb-4">
        <strong><?php echo count($notifications); ?></strong> thông báo 
        <?php if ($unreadCount > 0): ?>
            (<strong><?php echo $unreadCount; ?></strong> chưa đọc)
        <?php endif; ?>
    </div>

    <!-- Notifications List -->
    <div class="list-group">
        <?php if (empty($notifications)): ?>
            <div class="alert alert-secondary text-center">
                <p class="mb-0">Không có thông báo nào</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <?php 
                    // Generate link based on notification type
                    $notifLink = '#';
                    if ($notification['type'] === 'support_ticket' && $notification['related_id']) {
                        $notifLink = APP_URL . '/Admin/viewSupportTicket/' . $notification['related_id'];
                    }
                ?>
                <a href="<?php echo $notifLink; ?>" class="list-group-item list-group-item-action <?php echo !$notification['is_read'] ? 'active' : ''; ?>" 
                   onclick="<?php echo $notifLink === '#' ? 'handleNotification(event, ' . $notification['id'] . ')' : 'return true;'; ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <?php if (!$notification['is_read']): ?>
                                    <span class="badge bg-primary me-2">Mới</span>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($notification['title']); ?>
                            </h6>
                            <p class="mb-1 text-muted small">
                                <?php echo htmlspecialchars(substr($notification['message'], 0, 150)); ?>
                                <?php if (strlen($notification['message']) > 150): ?>...<?php endif; ?>
                            </p>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?>
                            </small>
                        </div>
                        <div class="ms-2">
                            <span class="badge bg-secondary">
                                <?php 
                                    $typeLabels = array(
                                        'support_ticket' => 'Hỗ Trợ',
                                        'order' => 'Đơn Hàng',
                                        'contract' => 'Hợp Đồng',
                                        'user' => 'Người Dùng'
                                    );
                                    echo isset($typeLabels[$notification['type']]) ? $typeLabels[$notification['type']] : $notification['type'];
                                ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.list-group-item {
    border-left: 4px solid #dee2e6;
    transition: all 0.3s ease;
}

.list-group-item.active {
    border-left-color: #0d6efd;
    background-color: #e7f1ff;
    color: inherit;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.list-group-item.active:hover {
    background-color: #d9e7ff;
}
</style>

<script>
function handleNotification(e, notificationId) {
    e.preventDefault();
    
    // Mark as read
    fetch('<?php echo APP_URL; ?>/Admin/markNotificationRead/' + notificationId)
        .then(r => r.json())
        .catch(err => console.error(err));
    
    // Reload to update UI
    location.reload();
}

function markAllAsRead() {
    if (confirm('Đánh dấu tất cả thông báo đã đọc?')) {
        fetch('<?php echo APP_URL; ?>/Admin/markAllNotificationsRead')
            .then(r => r.json())
            .then(data => {
                location.reload();
            })
            .catch(err => console.error(err));
    }
}

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    fetch('<?php echo APP_URL; ?>/Admin/getUnreadCount')
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                // Update badge on navbar if it exists
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = 'block';
                }
            }
        })
        .catch(err => console.error(err));
}, 30000);
</script>
