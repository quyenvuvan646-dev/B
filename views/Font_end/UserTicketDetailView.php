<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?php echo APP_URL; ?>/Support/myTickets" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
                <h4 style="margin: 0; color: #1e293b;">
                    <i class="bi bi-ticket-detailed"></i> Chi Tiết Yêu Cầu #<?php echo $ticket['id']; ?>
                </h4>
                <div style="width: 100px;"></div>
            </div>

            <!-- Ticket Card -->
            <div class="card mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin: 0;">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($ticket['user_email']); ?>
                            </h5>
                            <small>Gửi lúc: <?php echo date('d/m/Y H:i', strtotime($ticket['created_at'])); ?></small>
                        </div>
                        <span class="badge <?php 
                            echo $ticket['status'] == 'open' ? 'bg-warning' : 
                                ($ticket['status'] == 'in_progress' ? 'bg-info' : 'bg-success'); 
                        ?>" style="font-size: 0.9rem;">
                            <?php 
                                $statusText = [
                                    'open' => 'Đang chờ', 
                                    'in_progress' => 'Đang xử lý', 
                                    'closed' => 'Đã đóng'
                                ];
                                echo $statusText[$ticket['status']] ?? 'Đang chờ';
                            ?>
                        </span>
                    </div>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <!-- User Message -->
                    <div class="mb-4">
                        <h6 style="color: #64748b; margin-bottom: 15px;">
                            <i class="bi bi-chat-left-quote"></i> Tin Nhắn Của Bạn:
                        </h6>
                        <div class="alert alert-light" style="border-left: 4px solid #667eea; background-color: #f8f9fa;">
                            <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;">
                                <?php echo htmlspecialchars($ticket['message']); ?>
                            </p>
                        </div>
                    </div>

                    <?php if (!empty($ticket['admin_response'])): ?>
                        <!-- Admin Response -->
                        <div class="mb-3">
                            <h6 style="color: #10b981; margin-bottom: 15px;">
                                <i class="bi bi-reply-fill"></i> Phản Hồi Từ Admin:
                            </h6>
                            <div class="alert alert-success" style="border-left: 4px solid #10b981; background-color: #f0fdf4;">
                                <div style="display: flex; align-items: start; gap: 15px;">
                                    <div style="font-size: 2rem; color: #10b981;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;">
                                            <?php echo htmlspecialchars($ticket['admin_response']); ?>
                                        </p>
                                        <hr style="margin: 15px 0;">
                                        <small style="color: #059669;">
                                            <i class="bi bi-clock"></i>
                                            Phản hồi lúc: <?php echo date('d/m/Y H:i', strtotime($ticket['updated_at'])); ?>
                                        </small>
                                        
                                        <?php if (!empty($ticket['user_read_at'])): ?>
                                            <br>
                                            <small style="color: #64748b;">
                                                <i class="bi bi-check2-all"></i>
                                                Đã xem lúc: <?php echo date('d/m/Y H:i', strtotime($ticket['user_read_at'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No Response Yet -->
                        <div class="alert alert-info">
                            <div class="text-center" style="padding: 20px;">
                                <i class="bi bi-hourglass-split" style="font-size: 3rem; color: #0ea5e9;"></i>
                                <h5 style="margin-top: 15px; color: #0369a1;">Đang Chờ Phản Hồi</h5>
                                <p style="margin: 0; color: #64748b;">
                                    Admin sẽ phản hồi yêu cầu của bạn sớm nhất có thể. Vui lòng kiểm tra lại sau!
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Timeline -->
                    <div class="mt-4" style="border-top: 2px dashed #e2e8f0; padding-top: 20px;">
                        <h6 style="color: #64748b; margin-bottom: 15px;">
                            <i class="bi bi-clock-history"></i> Lịch Sử:
                        </h6>
                        <ul style="list-style: none; padding-left: 0;">
                            <li style="padding: 8px 0; color: #64748b;">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem; color: #3b82f6;"></i>
                                <strong>Tạo yêu cầu:</strong> <?php echo date('d/m/Y H:i:s', strtotime($ticket['created_at'])); ?>
                            </li>
                            <?php if (!empty($ticket['admin_response'])): ?>
                                <li style="padding: 8px 0; color: #64748b;">
                                    <i class="bi bi-circle-fill" style="font-size: 0.5rem; color: #10b981;"></i>
                                    <strong>Admin phản hồi:</strong> <?php echo date('d/m/Y H:i:s', strtotime($ticket['updated_at'])); ?>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($ticket['user_read_at'])): ?>
                                <li style="padding: 8px 0; color: #64748b;">
                                    <i class="bi bi-circle-fill" style="font-size: 0.5rem; color: #8b5cf6;"></i>
                                    <strong>Bạn đã xem:</strong> <?php echo date('d/m/Y H:i:s', strtotime($ticket['user_read_at'])); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="<?php echo APP_URL; ?>/Support/myTickets" class="btn btn-outline-primary" style="min-width: 150px;">
                    <i class="bi bi-list-ul"></i> Xem Tất Cả Yêu Cầu
                </a>
                <a href="<?php echo APP_URL; ?>/Support/Show" class="btn btn-primary" style="min-width: 150px; margin-left: 10px;">
                    <i class="bi bi-plus-circle"></i> Gửi Yêu Cầu Mới
                </a>
            </div>
        </div>
    </div>
</div>
