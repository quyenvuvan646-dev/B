<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 10px;">
                    <i class="bi bi-clock-history"></i> Lịch Sử Hỗ Trợ
                </h1>
                <p style="font-size: 1.1rem; color: #666;">
                    Xem tất cả các yêu cầu hỗ trợ của bạn
                </p>
            </div>

            <?php if (empty($tickets)): ?>
                <!-- No tickets -->
                <div class="card text-center" style="padding: 60px 20px;">
                    <div style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 style="color: #64748b; margin-bottom: 15px;">Chưa có yêu cầu hỗ trợ</h4>
                    <p style="color: #94a3b8; margin-bottom: 25px;">
                        Bạn chưa gửi yêu cầu hỗ trợ nào. Hãy liên hệ với chúng tôi khi cần giúp đỡ!
                    </p>
                    <a href="<?php echo APP_URL; ?>/Support/Show" class="btn btn-primary" style="max-width: 200px; margin: 0 auto;">
                        <i class="bi bi-chat-left-text"></i> Gửi Yêu Cầu Mới
                    </a>
                </div>
            <?php else: ?>
                <!-- Tickets list -->
                <div class="mb-3" style="text-align: right;">
                    <a href="<?php echo APP_URL; ?>/Support/Show" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Gửi Yêu Cầu Mới
                    </a>
                </div>

                <?php foreach ($tickets as $ticket): ?>
                    <?php 
                        $hasResponse = !empty($ticket['admin_response']);
                        $isUnread = $hasResponse && empty($ticket['user_read_at']);
                        
                        $statusColors = [
                            'open' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'clock'],
                            'in_progress' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'arrow-repeat'],
                            'closed' => ['bg' => '#dcfce7', 'text' => '#166534', 'icon' => 'check-circle']
                        ];
                        
                        $status = $ticket['status'];
                        $color = $statusColors[$status] ?? $statusColors['open'];
                    ?>
                    
                    <div class="card mb-3 <?php echo $isUnread ? 'border-danger' : ''; ?>" 
                         style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); <?php echo $isUnread ? 'border-width: 2px;' : ''; ?>">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Ticket Info -->
                                <div class="col-md-8">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                        <h5 style="margin: 0; color: #1e293b;">
                                            Yêu cầu #<?php echo $ticket['id']; ?>
                                        </h5>
                                        <span class="badge" style="background-color: <?php echo $color['bg']; ?>; color: <?php echo $color['text']; ?>;">
                                            <i class="bi bi-<?php echo $color['icon']; ?>"></i>
                                            <?php 
                                                $statusText = ['open' => 'Đang chờ', 'in_progress' => 'Đang xử lý', 'closed' => 'Đã đóng'];
                                                echo $statusText[$status] ?? 'Đang chờ';
                                            ?>
                                        </span>
                                        
                                        <?php if ($isUnread): ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-bell-fill"></i> Có phản hồi mới
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p style="color: #64748b; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo htmlspecialchars($ticket['message']); ?>
                                    </p>
                                    
                                    <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #94a3b8;">
                                        <span>
                                            <i class="bi bi-calendar"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($ticket['created_at'])); ?>
                                        </span>
                                        
                                        <?php if ($hasResponse): ?>
                                            <span style="color: #10b981;">
                                                <i class="bi bi-reply-fill"></i> Đã phản hồi
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="col-md-4 text-end">
                                    <a href="<?php echo APP_URL; ?>/Support/viewTicket/<?php echo $ticket['id']; ?>" 
                                       class="btn btn-<?php echo $isUnread ? 'danger' : 'outline-primary'; ?> w-100">
                                        <i class="bi bi-eye"></i>
                                        <?php echo $isUnread ? 'Xem Phản Hồi Mới' : 'Xem Chi Tiết'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
