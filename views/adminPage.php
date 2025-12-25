<!doctype html>
<html lang="en">
    <head>
        <title>Admin Panel</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Bootstrap CSS -->
        <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <!-- Bootstrap Bundle JS (có Popper) -->
        <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <style>
            .navbar-glass {
                background: rgba(255,255,255,0.92);
                border-bottom: 1px solid rgba(102,126,234,0.2);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 2px 15px rgba(102,126,234,0.1);
                position: relative;
                z-index: 1100;
                overflow: visible;
            }
            .navbar {
                overflow: visible;
            }
            .navbar .nav-link {
                color: #4a5568 !important;
                font-weight: 500;
                padding: 10px 14px;
                transition: all 0.3s ease;
            }
            .navbar .nav-link:hover {
                color: #667eea !important;
                background: rgba(102,126,234,0.08);
                border-radius: 6px;
            }
            .navbar .dropdown-toggle::after {
                border-color: #667eea;
            }
            .brand-mark {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #667eea;
                font-weight: 700;
                font-size: 1.3rem;
            }
            .brand-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea, #764ba2);
                box-shadow: 0 0 15px rgba(102,126,234,0.4);
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }
            .btn-admin {
                border: 1px solid rgba(102,126,234,0.3);
                color: #667eea;
                background: rgba(102,126,234,0.05);
                transition: all 0.3s ease;
            }
            .btn-admin:hover {
                background: rgba(102,126,234,0.15);
                border-color: #667eea;
                color: #667eea;
                transform: translateY(-2px);
            }
            body {
                background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
                min-height: 100vh;
            }
            main {
                padding: 20px 0;
                position: relative;
                z-index: 1;
            }
            .dropdown-menu {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(102,126,234,0.2);
                box-shadow: 0 8px 20px rgba(102,126,234,0.1);
                z-index: 2000 !important;
                position: absolute;
            }
            .dropdown-item {
                color: #4a5568 !important;
                transition: all 0.3s ease;
            }
            .dropdown-item:hover {
                background: rgba(102,126,234,0.08) !important;
                color: #667eea !important;
                padding-left: 2.5rem;
            }
            
            /* Admin Page Styles */
            .card {
                border: 1px solid rgba(102,126,234,0.1);
                background: rgba(255,255,255,0.9);
                box-shadow: 0 4px 15px rgba(102,126,234,0.05);
                transition: all 0.3s ease;
            }
            .card:hover {
                box-shadow: 0 8px 25px rgba(102,126,234,0.1);
                transform: translateY(-2px);
            }
            .card-header {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%);
                border-bottom: 1px solid rgba(102,126,234,0.1);
                color: #4a5568;
                font-weight: 600;
            }
            .table {
                background: transparent;
            }
            .table thead {
                background: linear-gradient(135deg, rgba(102,126,234,0.08) 0%, rgba(118,75,162,0.04) 100%);
                color: #667eea;
                font-weight: 600;
            }
            .table thead th {
                border-bottom: 2px solid rgba(102,126,234,0.2);
                color: #667eea;
            }
            .table tbody tr {
                transition: all 0.3s ease;
                border-bottom: 1px solid rgba(102,126,234,0.08);
            }
            .table tbody tr:hover {
                background-color: rgba(102,126,234,0.05);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea, #764ba2);
                border: none;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #5567d8, #663a90);
                transform: translateY(-2px);
                box-shadow: 0 8px 15px rgba(102,126,234,0.3);
            }
            .btn-secondary {
                background: rgba(102,126,234,0.1);
                border: 1px solid rgba(102,126,234,0.2);
                color: #667eea;
                transition: all 0.3s ease;
            }
            .btn-secondary:hover {
                background: rgba(102,126,234,0.2);
                border-color: #667eea;
                color: #667eea;
            }
            .btn-success {
                background: linear-gradient(135deg, #10b981, #059669);
                border: none;
            }
            .btn-success:hover {
                background: linear-gradient(135deg, #059669, #047857);
            }
            .btn-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626);
                border: none;
            }
            .btn-danger:hover {
                background: linear-gradient(135deg, #dc2626, #b91c1c);
            }
            .form-control, .form-select {
                border: 1px solid rgba(102,126,234,0.2);
                background: rgba(255,255,255,0.8);
                color: #4a5568;
            }
            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
                background: rgba(255,255,255,0.95);
            }
            .badge {
                font-weight: 600;
            }
            .badge-primary {
                background: linear-gradient(135deg, #667eea, #764ba2);
            }
            .badge-success {
                background: linear-gradient(135deg, #10b981, #059669);
            }
            .badge-warning {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }
            .badge-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }
            .container {
                max-width: 1200px;
            }
            h1, h2, h3, h4, h5, h6 {
                color: #4a5568;
                font-weight: 600;
            }
            .text-muted {
                color: #9099a7 !important;
            }
        </style>
    </head>
    
    <body>
        <header>
        <nav class="navbar navbar-expand-lg navbar-glass">
            <div class="container">
                <a class="navbar-brand brand-mark" href="<?php echo APP_URL;?>/Admin/">
                    <span class="brand-dot"></span>
                    <i class="bi bi-shield-check"></i> Admin Panel
                </a>
                <button
                    class="navbar-toggler d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapsibleNavId"
                    aria-controls="collapsibleNavId"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php
                    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
                    require_once __DIR__ . '/../app/Auth.php';
                    $currentUser = \Auth::user();
                    $isAdmin = \Auth::hasRole('admin');
                    $roleNames = [];
                    if ($currentUser) {
                        $uid = $currentUser['user_id'] ?? $currentUser['id'] ?? null;
                        if (!empty($uid)) {
                            require_once __DIR__ . '/../models/UserModel.php';
                            $um = new UserModel();
                            $roles = $um->getUserRolesById($uid);
                            foreach ($roles as $r) $roleNames[] = $r['name'];
                        } else if (isset($currentUser['email']) && defined('SUPER_ADMIN_EMAIL') && strtolower($currentUser['email']) === strtolower(SUPER_ADMIN_EMAIL)) {
                            $roleNames[] = 'Admin';
                            $isAdmin = true;
                        }
                    }
                ?>

                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownQuanly" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-kanban"></i> Quản Lý
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownQuanly">
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/ProductType/"><i class="bi bi-tags"></i> Loại Sản Phẩm</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Product/"><i class="bi bi-box"></i> Sản Phẩm</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/shops"><i class="bi bi-shop"></i> Danh sách Shop</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/khuyenmai/"><i class="bi bi-percent"></i> Khuyến Mãi</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/voucher/"><i class="bi bi-ticket"></i> Voucher</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/banners"><i class="bi bi-images"></i> Banner Slider</a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/returnedOrders/"><i class="bi bi-arrow-return-left"></i> Đơn Trả</a>
                            </div>
                        </li>

                        <?php if ($isAdmin): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownAdmin" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-shield-check"></i> Admin
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownAdmin">
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/users/"><i class="bi bi-people"></i> Người Dùng</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/comments/"><i class="bi bi-chat-left-text"></i> Bình Luận</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/statistics/"><i class="bi bi-bar-chart"></i> Thống Kê</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/orders/"><i class="bi bi-cart-check"></i> Đơn Hàng</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/News/adminIndex"><i class="bi bi-newspaper"></i> Tin Tức</a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/vouchers"><i class="bi bi-ticket-perforated"></i> Voucher Admin</a>
                                <a class="dropdown-item" href="<?php echo APP_URL;?>/Admin/contractsManagement"><i class="bi bi-file-earmark-text"></i> Hợp Đồng</a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ($currentUser): ?>
                        <div class="d-flex align-items-center gap-3">
                            <?php if ($isAdmin): ?>
                            <!-- Notification Bell -->
                            <a href="<?php echo APP_URL; ?>/Admin/notifications" class="btn btn-admin position-relative">
                                <i class="bi bi-bell"></i>
                                <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.65rem;">0</span>
                            </a>
                            
                            <!-- Support Chat -->
                            <a href="<?php echo APP_URL; ?>/Admin/supportChat" class="btn btn-admin">
                                <i class="bi bi-chat-dots"></i> Chat
                            </a>
                            <?php endif; ?>
                            
                            <!-- User Info -->
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2" style="font-size: 1.5rem; color: #667eea;"></i>
                                <div>
                                    <small class="d-block fw-bold" style="color: #4a5568;"><?php echo htmlspecialchars($currentUser['fullname'] ?? $currentUser['email']); ?></small>
                                    <?php if (!empty($roleNames)): ?>
                                        <small class="d-block" style="color: #9099a7;"><?php echo implode(', ', array_map('htmlspecialchars', $roleNames)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo APP_URL; ?>/AuthController/logout" class="btn btn-admin ms-2">
                                <i class="bi bi-box-arrow-right"></i> Đăng xuất
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        
        </header>
        <main>
            <?php
              require_once "./views/Back_end/".$data["page"].".php";
            ?>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        
        <!-- Notification Badge Script -->
        <script>
        // Load unread notification count
        function loadNotificationCount() {
            fetch('<?php echo APP_URL; ?>/Admin/getUnreadCount')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'block';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => console.error('Error loading notification count:', err));
        }
        
        // Load on page load
        <?php if ($isAdmin ?? false): ?>
        document.addEventListener('DOMContentLoaded', function() {
            loadNotificationCount();
            // Disabled auto-refresh to prevent network spam and page issues
            // setInterval(loadNotificationCount, 300000);
        });
        <?php endif; ?>
        </script>
    </body>
</html>
