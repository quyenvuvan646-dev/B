<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <a href="<?php echo APP_URL; ?>/Admin/contractsManagement" class="btn btn-secondary mb-3">
                <i class="bi bi-chevron-left"></i> Quay Lại
            </a>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 style="margin: 0;">
                        <i class="bi bi-file-earmark-text"></i> Chi Tiết Hợp Đồng #<?php echo $contract['id']; ?>
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Status Badge -->
                    <div style="margin-bottom: 20px;">
                        <?php
                            $statusColor = match($contract['status']) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'secondary'
                            };
                            $statusText = match($contract['status']) {
                                'pending' => 'Chờ Duyệt',
                                'approved' => 'Đã Duyệt',
                                'rejected' => 'Bị Từ Chối',
                                default => $contract['status']
                            };
                        ?>
                        <span class="badge bg-<?php echo $statusColor; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <?php echo $statusText; ?>
                        </span>
                    </div>

                    <!-- User Information -->
                    <h5 style="margin-top: 25px; margin-bottom: 15px;">👤 Thông Tin Người Dùng</h5>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <div style="margin-bottom: 10px;">
                            <strong>Email:</strong> <?php echo htmlspecialchars($contract['user_email']); ?>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Họ Tên:</strong> <?php echo htmlspecialchars($contract['full_name']); ?>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($contract['phone']); ?>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <h5 style="margin-top: 25px; margin-bottom: 15px;">🏢 Thông Tin Công Ty</h5>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <div style="margin-bottom: 10px;">
                            <strong>Tên Công Ty:</strong> <?php echo htmlspecialchars($contract['company_name']); ?>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($contract['business_address']); ?>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Mã Số Thuế:</strong> <?php echo htmlspecialchars($contract['tax_id'] ?? 'N/A'); ?>
                        </div>
                        <div>
                            <strong>Giấy Phép Kinh Doanh:</strong> <?php echo htmlspecialchars($contract['business_license'] ?? 'N/A'); ?>
                        </div>
                    </div>

                    <!-- Contract File -->
                    <?php if ($contract['contract_file']): ?>
                        <h5 style="margin-top: 25px; margin-bottom: 15px;">📄 File Hợp Đồng</h5>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                            <a href="<?php echo APP_URL; ?>/<?php echo htmlspecialchars($contract['contract_file']); ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary">
                                <i class="bi bi-download"></i> Tải File Hợp Đồng
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Dates -->
                    <h5 style="margin-top: 25px; margin-bottom: 15px;">📅 Thông Tin Thời Gian</h5>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <div style="margin-bottom: 10px;">
                            <strong>Ngày Đăng Ký:</strong> <?php echo date('d/m/Y H:i', strtotime($contract['created_at'])); ?>
                        </div>
                        <div>
                            <strong>Cập Nhật Lần Cuối:</strong> <?php echo date('d/m/Y H:i', strtotime($contract['updated_at'])); ?>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <?php if ($contract['admin_notes']): ?>
                        <h5 style="margin-top: 25px; margin-bottom: 15px;">📝 Ghi Chú Admin</h5>
                        <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; border-left: 4px solid #6c757d; margin-bottom: 20px;">
                            <?php echo htmlspecialchars($contract['admin_notes']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Panel (Right Side) -->
        <div class="col-md-4">
            <?php if ($contract['status'] === 'pending'): ?>
                <!-- APPROVE FORM -->
                <div class="card border-success mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 style="margin: 0;">✅ Duyệt Hợp Đồng</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo APP_URL; ?>/Admin/approveContract/<?php echo $contract['id']; ?>">
                            <div class="mb-3">
                                <label for="approve_notes" class="form-label">Ghi Chú (Tùy Chọn)</label>
                                <textarea class="form-control" id="approve_notes" name="admin_notes" rows="3" 
                                          placeholder="Nhập ghi chú (tùy chọn)"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Duyệt & Nâng Cấp
                            </button>
                            <small class="text-muted d-block mt-2">Người dùng sẽ trở thành Nhà Phân Phối</small>
                        </form>
                    </div>
                </div>

                <!-- REJECT FORM -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 style="margin: 0;">❌ Từ Chối Hợp Đồng</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo APP_URL; ?>/Admin/rejectContract/<?php echo $contract['id']; ?>">
                            <div class="mb-3">
                                <label for="reject_notes" class="form-label">Lý Do Từ Chối <span class="text-danger">*</span></label>
                                <textarea class="form-control is-invalid" id="reject_notes" name="admin_notes" rows="3" 
                                          placeholder="Nhập lý do từ chối (bắt buộc)" required></textarea>
                                <small class="text-danger d-block mt-2">Bắt buộc phải nhập lý do</small>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle"></i> Từ Chối
                            </button>
                        </form>
                    </div>
                </div>
            <?php elseif ($contract['status'] === 'approved'): ?>
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 style="margin: 0;">✅ Đã Duyệt</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Hợp đồng này đã được duyệt
                        </div>
                        <p><strong>Trạng Thái:</strong> Người dùng đã được nâng cấp thành Nhà Phân Phối</p>
                        <?php if ($contract['admin_notes']): ?>
                            <p><strong>Ghi Chú:</strong></p>
                            <p style="background: #f0f0f0; padding: 10px; border-radius: 5px;">
                                <?php echo htmlspecialchars($contract['admin_notes']); ?>
                            </p>
                        <?php endif; ?>
                        <!-- Delete Approved Contract -->
                        <hr>
                        <form method="POST" action="<?php echo APP_URL; ?>/Admin/deleteContract/<?php echo $contract['id']; ?>" style="display: inline;">
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Bạn có chắc chắn muốn xóa hợp đồng này?')">
                                <i class="bi bi-trash"></i> Xóa Hợp Đồng
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 style="margin: 0;">❌ Bị Từ Chối</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i> Hợp đồng này đã bị từ chối
                        </div>
                        <p><strong>Lý Do:</strong></p>
                        <p style="background: #f0f0f0; padding: 10px; border-radius: 5px;">
                            <?php echo htmlspecialchars($contract['admin_notes'] ?? 'Không có lý do'); ?>
                        </p>
                        <!-- Delete Rejected Contract -->
                        <hr>
                        <form method="POST" action="<?php echo APP_URL; ?>/Admin/deleteContract/<?php echo $contract['id']; ?>" style="display: inline;">
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Bạn có chắc chắn muốn xóa hợp đồng này?')">
                                <i class="bi bi-trash"></i> Xóa Hợp Đồng
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 1rem;
    }

    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
</style>
