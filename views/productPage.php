<!doctype html>
<html lang="vi">
<head>
    <title>Danh sách sản phẩm - Phương Nam Marketplace</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="<?php echo APP_URL;?>/public/css/products-dark.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <style>
        .navbar-glass {
            background: rgba(10,14,25,0.9);
            border-bottom: 1px solid rgba(148,163,184,0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .navbar .nav-link {
            color: #e2e8f0 !important;
            font-weight: 500;
            padding: 10px 12px;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: #93c5fd !important;
        }
        .brand-mark {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .brand-dot {
            width: 8px;
            height: 8px;
            background: #60a5fa;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .btn-ghost {
            background: rgba(59, 89, 152, 0.1);
            border: 1px solid #3b5998;
            color: #93c5fd;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-ghost:hover {
            background: #3b5998;
            color: white;
        }
        .product-page-container {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            padding-top: 80px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand brand-mark" href="<?php echo APP_URL; ?>/Home">
                <span class="brand-dot"></span> Phương Nam
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/Home/products">
                            <i class="bi bi-box2"></i> Sản phẩm
                        </a>
                    </li>
                    <?php 
                    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                    if (isset($_SESSION['user'])): 
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Home/cart">
                                <i class="bi bi-cart3"></i> Giỏ hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/Home/orderHistory">
                                <i class="bi bi-receipt"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-ghost" href="<?php echo APP_URL; ?>/AuthController/logout">
                                <i class="bi bi-box-arrow-right"></i> Đăng xuất
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn-ghost" href="<?php echo APP_URL; ?>/AuthController/ShowLogin">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="product-page-container">
        <?php
        if (isset($data['page']) && file_exists("./views/Font_end/" . $data['page'] . ".php")) {
            require "./views/Font_end/" . $data['page'] . ".php";
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="section-dark py-5 border-top border-secondary mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Về Phương Nam</h6>
                    <ul class="list-unstyled subtitle-muted small">
                        <li><a href="#" class="text-decoration-none">Giới thiệu</a></li>
                        <li><a href="#" class="text-decoration-none">Tuyển dụng</a></li>
                        <li><a href="#" class="text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Hỗ trợ</h6>
                    <ul class="list-unstyled subtitle-muted small">
                        <li><a href="#" class="text-decoration-none">Liên hệ</a></li>
                        <li><a href="#" class="text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-decoration-none">Chính sách</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Theo dõi</h6>
                    <ul class="list-unstyled subtitle-muted small">
                        <li><a href="#" class="text-decoration-none"><i class="bi bi-facebook"></i> Facebook</a></li>
                        <li><a href="#" class="text-decoration-none"><i class="bi bi-instagram"></i> Instagram</a></li>
                        <li><a href="#" class="text-decoration-none"><i class="bi bi-twitter"></i> Twitter</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Thanh toán</h6>
                    <p class="subtitle-muted small">Hỗ trợ: COD, VNPAY, Chuyển khoản</p>
                </div>
            </div>
            <hr class="border-secondary my-4" />
            <div class="text-center subtitle-muted small">
                <p>&copy; 2025 Phương Nam Marketplace. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
</body>
</html>
