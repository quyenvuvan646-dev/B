<!doctype html>
<html lang="en">
    <head>
        <title>Nhà phân phối</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <style>
            .navbar-glass {
                background: rgba(255,193,7,0.95);
                border-bottom: 1px solid rgba(0,0,0,0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .navbar .nav-link {
                color: rgba(0,0,0,0.8) !important;
                font-weight: 500;
                padding: 10px 14px;
                transition: all 0.3s ease;
            }
            .navbar .nav-link:hover {
                color: #000 !important;
                background: rgba(0,0,0,0.1);
                border-radius: 6px;
            }
            .brand-mark {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #000;
                font-weight: 700;
                font-size: 1.3rem;
            }
            .brand-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: linear-gradient(135deg, #ff6b6b, #ffc107);
                box-shadow: 0 0 15px rgba(255,107,107,0.6);
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }
            .btn-dist {
                border: 1px solid rgba(0,0,0,0.3);
                color: #000;
                background: rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }
            .btn-dist:hover {
                background: rgba(0,0,0,0.15);
                border-color: #000;
                color: #000;
                transform: translateY(-1px);
            }
            body {
                background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
                min-height: 100vh;
            }
            main {
                padding: 20px 0;
            }
        </style>
    </head>
    
    <body>
        <header>
        <nav class="navbar navbar-expand-lg navbar-glass">
            <div class="container">
                <a class="navbar-brand brand-mark" href="<?php echo APP_URL;?>/Distributor/">
                    <span class="brand-dot"></span>
                    <i class="bi bi-building"></i> Nhà Phân Phối
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
                    $roleNames = [];
                    if ($currentUser) {
                        $uid = $currentUser['user_id'] ?? $currentUser['id'] ?? null;
                        if (!empty($uid)) {
                            require_once __DIR__ . '/../models/UserModel.php';
                            $um = new UserModel();
                            $roles = $um->getUserRolesById($uid);
                            foreach ($roles as $r) $roleNames[] = $r['name'];
                        }
                    }
                ?>

                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/statistics"><i class="bi bi-graph-up"></i> Thống kê</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/productTypes"><i class="bi bi-tags"></i> Loại SP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/products"><i class="bi bi-box-seam"></i> Sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/orders"><i class="bi bi-cart-check"></i> Đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?php echo APP_URL;?>/Distributor/messages">
                                <i class="bi bi-chat-dots"></i> Tin nhắn
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                      id="distributorMessageBadge" 
                                      style="display: none; font-size: 0.7rem;">
                                    0
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/shippingRules"><i class="bi bi-truck"></i> Vận chuyển</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/vouchers"><i class="bi bi-ticket-perforated"></i> Voucher</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL;?>/Distributor/createProduct"><i class="bi bi-plus-circle"></i> Thêm SP</a>
                        </li>
                    </ul>

                    <?php if ($currentUser): ?>
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <small class="d-block fw-bold"><?php echo htmlspecialchars($currentUser['fullname'] ?? $currentUser['email']); ?></small>
                                    <?php if (!empty($roleNames)): ?>
                                        <small class="d-block opacity-75"><?php echo implode(', ', array_map('htmlspecialchars', $roleNames)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo APP_URL; ?>/AuthController/showChangePassword" class="btn btn-dist ms-2">
                                <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                            </a>
                            <a href="<?php echo APP_URL; ?>/AuthController/logout" class="btn btn-dist">
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

        <script>
            // Load unread message count for distributor
            <?php if (isset($_SESSION['user'])): ?>
            function loadDistributorMessageBadge() {
                fetch('<?= APP_URL ?>/api_unread_messages.php')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('distributorMessageBadge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count;
                                badge.style.display = 'inline-block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    })
                    .catch(err => console.error('Error loading message count:', err));
            }
            
            // Load on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadDistributorMessageBadge);
            } else {
                loadDistributorMessageBadge();
            }
            
            // Auto-refresh every 30 seconds
            setInterval(loadDistributorMessageBadge, 30000);
            <?php endif; ?>
        </script>
        <footer>
            <!-- place footer here -->
            
        </footer>
    </body>
</html>
