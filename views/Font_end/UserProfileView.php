<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; color: white; font-size: 28px;">
                        👤
                    </div>
                    <div>
                        <h2 class="mb-0">Thông Tin Cá Nhân</h2>
                        <small class="text-muted"><?= htmlspecialchars($data['user']['email']); ?></small>
                    </div>
                </div>
                <hr>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Profile Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📋 Thông Tin Cá Nhân</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= APP_URL ?>/Home/updateProfile" id="profileForm">
                        <!-- Họ và Tên -->
                        <div class="mb-3">
                            <label for="fullname" class="form-label fw-bold">
                                Họ và Tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="fullname" 
                                   name="fullname" 
                                   value="<?= htmlspecialchars($data['user']['fullname'] ?? ''); ?>" 
                                   required
                                   minlength="2"
                                   placeholder="Nhập họ và tên">
                            <small class="text-muted">Tối thiểu 2 ký tự</small>
                        </div>

                        <!-- Email (Read-only) -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                Email <span class="text-muted">(không thể thay đổi)</span>
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   value="<?= htmlspecialchars($data['user']['email']); ?>" 
                                   readonly>
                        </div>

                        <!-- Số Điện Thoại -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">
                                📞 Số Điện Thoại
                            </label>
                            <input type="tel" 
                                   class="form-control form-control-lg" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?= htmlspecialchars($data['user']['phone'] ?? ''); ?>" 
                                   placeholder="Ví dụ: 0987654321">
                        </div>

                        <!-- Địa Chỉ -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">
                                📍 Địa Chỉ
                            </label>
                            <textarea class="form-control form-control-lg" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Nhập địa chỉ của bạn"><?= htmlspecialchars($data['user']['address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info alert-sm mb-4" role="alert">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                <strong>Mẹo:</strong> Cập nhật thông tin sẽ giúp quá trình giao hàng nhanh hơn và chính xác hơn.
                            </small>
                        </div>

                        <!-- Button Group -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Cập Nhật Thông Tin
                            </button>
                            <a href="<?= APP_URL ?>/Home/show" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">ℹ️ Tài Khoản</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Account Created -->
                        <div class="col-md-6">
                            <div class="ps-3 border-start border-3 border-primary">
                                <small class="text-muted d-block">Ngày tạo tài khoản</small>
                                <div class="fw-bold">
                                    <?php 
                                    $created = $data['user']['created_at'] ?? null;
                                    echo $created ? date('d/m/Y H:i', strtotime($created)) : 'N/A'; 
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="col-md-6">
                            <div class="ps-3 border-start border-3 border-success">
                                <small class="text-muted d-block">Trạng thái tài khoản</small>
                                <div class="fw-bold">
                                    <?php 
                                    $locked = $data['user']['is_locked'] ?? false;
                                    if ($locked) {
                                        echo '<span class="badge bg-danger">🔒 Bị khóa</span>';
                                    } else {
                                        echo '<span class="badge bg-success">✅ Hoạt động</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Email Verified -->
                        <div class="col-md-6">
                            <div class="ps-3 border-start border-3 border-info">
                                <small class="text-muted d-block">Email</small>
                                <div class="fw-bold">
                                    <?php 
                                    $verified = $data['user']['is_verified'] ?? false;
                                    if ($verified) {
                                        echo '<span class="badge bg-success">✓ Đã xác minh</span>';
                                    } else {
                                        echo '<span class="badge bg-warning">⚠ Chưa xác minh</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="col-md-6">
                            <div class="ps-3 border-start border-3 border-warning">
                                <small class="text-muted d-block">Loại tài khoản</small>
                                <div class="fw-bold">
                                    <?php 
                                    $roleId = $data['user']['user_role'] ?? 1;
                                    $roles = ['1' => '🛒 Người mua', '2' => '🏪 Nhà phân phối', '3' => '🚚 Shipper', '4' => '👨‍💼 Admin'];
                                    echo $roles[$roleId] ?? 'Không xác định';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-outline-primary">
                    <i class="bi bi-bag"></i> Lịch sử đơn hàng
                </a>
                <a href="<?= APP_URL ?>/Home/supportTickets" class="btn btn-outline-secondary">
                    <i class="bi bi-chat-dots"></i> Hỗ trợ
                </a>
                <a href="<?= APP_URL ?>/Home/show" class="btn btn-outline-success">
                    <i class="bi bi-house"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-control-lg:focus,
    textarea:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .border-start {
        border-width: 4px !important;
    }
</style>
