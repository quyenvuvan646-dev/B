<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="<?php echo APP_URL; ?>/Admin/notifications" class="btn btn-outline-secondary mb-3">
                ← Quay lại thông báo
            </a>
            <h2 class="fw-bold">💬 Chi tiết yêu cầu hỗ trợ</h2>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Ticket Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin người gửi</h5>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($ticket['user_email']); ?></p>
                    <p><strong>Ngày gửi:</strong> <?php echo date('d/m/Y H:i:s', strtotime($ticket['created_at'])); ?></p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge bg-<?php 
                            echo $ticket['status'] === 'open' ? 'warning' : 
                                ($ticket['status'] === 'in_progress' ? 'info' : 'success'); 
                        ?>">
                            <?php 
                                $statusText = array(
                                    'open' => 'Mở',
                                    'in_progress' => 'Đang xử lý',
                                    'closed' => 'Đã đóng'
                                );
                                echo $statusText[$ticket['status']] ?? $ticket['status'];
                            ?>
                        </span>
                    </p>
                </div>
            </div>

            <!-- User Message -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Nội dung yêu cầu</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border">
                        <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                    </div>
                </div>
            </div>

            <!-- Admin Response -->
            <?php if ($ticket['admin_response']): ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Phản hồi của Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success border">
                            <?php echo nl2br(htmlspecialchars($ticket['admin_response'])); ?>
                        </div>
                        <small class="text-muted">
                            Phản hồi lúc: <?php echo date('d/m/Y H:i:s', strtotime($ticket['updated_at'])); ?>
                        </small>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Reply Form -->
            <?php if ($ticket['status'] !== 'closed'): ?>
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Gửi phản hồi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo APP_URL; ?>/Admin/replyToTicket">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="admin_response" class="form-label">Nội dung phản hồi *</label>
                                <textarea 
                                    class="form-control" 
                                    id="admin_response" 
                                    name="admin_response" 
                                    rows="6" 
                                    required
                                    placeholder="Nhập nội dung phản hồi cho người dùng..."
                                ><?php echo $ticket['admin_response'] ?? ''; ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $ticket['admin_response'] ? 'Cập nhật phản hồi' : 'Gửi phản hồi'; ?>
                                </button>
                                <a href="<?php echo APP_URL; ?>/Admin/notifications" class="btn btn-outline-secondary">
                                    Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <strong>✓ Ticket đã được đóng</strong>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:<?php echo htmlspecialchars($ticket['user_email']); ?>" class="btn btn-outline-primary">
                            📧 Gửi Email
                        </a>
                        <button class="btn btn-outline-info" onclick="copyEmail()">
                            📋 Copy Email
                        </button>
                        <a href="<?php echo APP_URL; ?>/Admin/notifications" class="btn btn-outline-secondary">
                            🔔 Xem tất cả thông báo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ticket Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>ID:</strong> #<?php echo $ticket['id']; ?></p>
                    <p class="mb-2"><strong>Tạo:</strong> <?php echo date('d/m/Y', strtotime($ticket['created_at'])); ?></p>
                    <p class="mb-0"><strong>Cập nhật:</strong> <?php echo date('d/m/Y', strtotime($ticket['updated_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyEmail() {
    const email = '<?php echo htmlspecialchars($ticket['user_email']); ?>';
    navigator.clipboard.writeText(email).then(() => {
        alert('Email đã được copy: ' + email);
    });
}
</script>
