<!doctype html>
<html lang="en">
    <head>
        <title>Phương Nam Marketplace</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="<?php echo APP_URL;?>/public/css/products-dark.css" rel="stylesheet" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <!-- Bootstrap Bundle JS (có Popper) -->
        <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <style>
            * {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            }

            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
                color: #e2e8f0;
                min-height: 100vh;
            }

            /* Premium Navbar */
            .navbar-premium {
                background: rgba(15, 23, 42, 0.95);
                border-bottom: 1px solid rgba(148, 163, 184, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                position: sticky;
                top: 0;
                z-index: 1100;
            }

            .navbar-premium .navbar-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 700;
                font-size: 1.25rem;
                color: #f8fafc !important;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                transition: all 0.3s ease;
            }

            .navbar-premium .navbar-brand:hover {
                transform: translateY(-2px);
            }

            .brand-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
                color: white;
                font-weight: 700;
            }

            .navbar-premium .nav-link {
                color: #cbd5e1 !important;
                font-weight: 500;
                font-size: 0.95rem;
                padding: 8px 16px !important;
                border-radius: 6px;
                transition: all 0.3s ease;
                position: relative;
            }

            .navbar-premium .nav-link:hover {
                color: #667eea !important;
                background: rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }

            .navbar-premium .nav-link.active {
                color: #667eea !important;
                background: rgba(102, 126, 234, 0.15);
                border-bottom: 2px solid #667eea;
            }

            /* Search Box */
            .search-premium {
                border-radius: 25px;
                border: 1px solid rgba(148, 163, 184, 0.2);
                background: rgba(30, 41, 59, 0.8);
                color: #e2e8f0;
                padding: 10px 20px;
                transition: all 0.3s ease;
            }

            .search-premium:focus {
                border-color: #667eea;
                background: rgba(30, 41, 59, 0.95);
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
                color: #e2e8f0;
                outline: none;
            }

            .search-premium::placeholder {
                color: #94a3b8;
            }

            /* Navbar Buttons & Icons */
            .navbar-icons {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .navbar-icons a, .navbar-icons button {
                padding: 8px 12px;
                border-radius: 8px;
                color: #cbd5e1;
                transition: all 0.3s ease;
                border: none;
                background: transparent;
                cursor: pointer;
            }

            .navbar-icons a:hover, .navbar-icons button:hover {
                color: #667eea;
                background: rgba(102, 126, 234, 0.1);
                transform: scale(1.05);
            }

            .btn-auth {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white !important;
                padding: 8px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                border: none;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .btn-auth:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
                color: white !important;
            }

            .navbar-text {
                font-size: 0.9rem;
                color: #cbd5e1;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .navbar-toggler {
                border: 1px solid rgba(148, 163, 184, 0.2) !important;
                padding: 6px 10px;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
            }

            /* Badge Styling */
            .badge-notification {
                position: absolute;
                top: -8px;
                right: -8px;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.65rem;
                font-weight: 700;
                box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
            }

            .position-notification {
                position: relative;
                display: inline-block;
            }

            /* Dropdown Menu Compact */
            .dropdown-menu {
                padding: 4px 0 !important;
                min-width: 170px;
            }

            .dropdown-menu .dropdown-item {
                padding: 6px 12px !important;
                font-size: 0.85rem;
                color: #cbd5e1 !important;
                transition: all 0.2s ease;
            }

            .dropdown-menu .dropdown-item:hover {
                background: rgba(102, 126, 234, 0.15) !important;
                color: #667eea !important;
            }

            .dropdown-menu .dropdown-item i {
                margin-right: 6px;
                width: 14px;
            }

            .dropdown-menu .dropdown-divider {
                margin: 2px 0 !important;
            }

            /* Responsive */
            @media (max-width: 991px) {
                .navbar-collapse {
                    background: rgba(15, 23, 42, 0.98);
                    border-top: 1px solid rgba(148, 163, 184, 0.1);
                    margin-top: 10px;
                    padding: 15px;
                    border-radius: 12px;
                    backdrop-filter: blur(10px);
                }

                .navbar-icons {
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-top: 15px;
                    padding-top: 15px;
                    border-top: 1px solid rgba(148, 163, 184, 0.1);
                }

                .navbar-icons a {
                    flex: 1;
                    text-align: center;
                    min-width: 100px;
                }

                .btn-auth {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    </head>

    <?php $pageClass = isset($data["page"]) && $data["page"] === "HomeView" ? "products-page" : ""; ?>
    <body class="<?php echo $pageClass; ?>">
        <header>
            <!-- Premium Navbar -->
            <nav class="navbar navbar-expand-lg navbar-premium">
                <div class="container-fluid px-3 px-lg-4">
                    <a class="navbar-brand" href="<?php echo APP_URL;?>/Home/">
                        <span class="brand-icon">P</span>
                        <span>Phương Nam</span>
                    </a>
                    <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav ms-auto mt-3 mt-lg-0 me-lg-3">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL;?>/Home/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL;?>/Home/products">Product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL;?>/News/list">News</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL;?>/Home/compare">Compare</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL;?>/Home/landing">About</a>

                                
                            </li>
                           
                        </ul>
                        <form class="d-flex d-lg-flex gap-2 mb-3 mb-lg-0" action="<?php echo APP_URL; ?>/Home/search" method="get">
                            <div class="input-group search-premium" style="border-radius: 25px; overflow: hidden;">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="q"
                                    placeholder="Tìm sản phẩm hoặc shop..."
                                    value="<?php echo isset($data['searchTerm']) ? htmlspecialchars($data['searchTerm']) : ''; ?>"
                                    required
                                    style="border: none; background: transparent; color: #e2e8f0;"
                                />
                                <select name="search_type" class="form-select" style="width: auto; border: none; background: rgba(102, 126, 234, 0.1); color: #cbd5e1;">
                                    <option value="products">Sản phẩm</option>
                                    <option value="shops">Shop</option>
                                </select>
                            </div>
                            <button class="btn btn-outline-light d-flex align-items-center justify-content-center" type="submit" style="padding: 10px 16px; border-radius: 8px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                        <div class="navbar-icons ms-lg-3">
                            <?php if (isset($_SESSION['user'])): ?>
                                <div class="dropdown">
                                    <button class="navbar-text btn btn-link text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-circle"></i>
                                        <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(148, 163, 184, 0.2);">
                                        <li><a class="dropdown-item text-light" href="<?php echo APP_URL; ?>/Home/profile">
                                            <i class="bi bi-person"></i> Thông tin cá nhân
                                        </a></li>
                                        <li><a class="dropdown-item text-light" href="<?php echo APP_URL; ?>/Home/orderHistory">
                                            <i class="bi bi-clock-history"></i> Lịch sử đơn hàng
                                        </a></li>
                                        <li><a class="dropdown-item text-light" href="<?php echo APP_URL; ?>/AuthController/showChangePassword">
                                            <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                                        </a></li>
                                    </ul>
                                </div>
                                <a href="<?php echo APP_URL; ?>/Home/order" class="position-notification" title="Giỏ hàng">
                                    <i class="bi bi-cart3"></i>
                                </a>

                                <a href="<?php echo APP_URL; ?>/Support/myTickets" class="position-notification" title="Hỗ trợ">
                                    <i class="bi bi-headset"></i>
                                    <span class="badge-notification" id="navbarSupportBadge" style="display: none;">0</span>
                                </a>
                                <a href="<?php echo APP_URL; ?>/Home/messages" class="position-notification" title="Tin nhắn">
                                    <i class="bi bi-chat-dots"></i>
                                    <span class="badge-notification" id="navbarMessageBadge" style="display: none;">0</span>
                                </a>
                                <?php if (($_SESSION['user']['role'] ?? 'user') !== 'distributor'): ?>
                                    <a href="<?php echo APP_URL; ?>/Home/distributorRegister" class="position-notification" title="Đăng ký kinh doanh">
                                        <i class="bi bi-building"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo APP_URL; ?>/AuthController/logout" class="btn-auth">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="d-none d-md-inline"></span>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn-auth">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    <span>Đăng nhập</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <?php
              require_once "./views/Font_end/".$data["page"].".php";
            ?>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script>
            (function() {
                var page = <?php echo json_encode($data["page"] ?? ""); ?>;
                if (page === 'HomeView') {
                    document.documentElement.classList.add('products-page');
                }
            })();
            
            <?php if (isset($_SESSION['user'])): ?>
            // Auto-refresh navbar support badge
            function loadNavbarSupportBadge() {
                fetch('<?php echo APP_URL; ?>/?url=Support/getUnreadResponseCount')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('navbarSupportBadge');
                        if (badge && data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(err => console.error('Error loading navbar support badge:', err));
            }
            
            // Load on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadNavbarSupportBadge);
            } else {
                loadNavbarSupportBadge();
            }
            
            // Auto-refresh every 30 seconds
            setInterval(loadNavbarSupportBadge, 30000);
            <?php endif; ?>

            // Load unread message count
            <?php if (isset($_SESSION['user'])): ?>
            function loadNavbarMessageBadge() {
                fetch('<?= APP_URL ?>/api_unread_messages.php')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('navbarMessageBadge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    })
                    .catch(err => console.error('Error loading message count:', err));
            }
            
            // Load on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadNavbarMessageBadge);
            } else {
                loadNavbarMessageBadge();
            }
            
            // Auto-refresh every 30 seconds
            setInterval(loadNavbarMessageBadge, 30000);
            <?php endif; ?>

            // Navbar scroll effect - hide on scroll down, show on scroll up
            let lastScrollTop = 0;
            const navbar = document.querySelector('.navbar-premium');
            const scrollThreshold = 100;

            window.addEventListener('scroll', function() {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > scrollThreshold) {
                    if (scrollTop > lastScrollTop) {
                        // Scrolling down - hide navbar
                        navbar.style.transform = 'translateY(-100%)';
                        navbar.style.transition = 'transform 0.3s ease-in-out';
                    } else {
                        // Scrolling up - show navbar
                        navbar.style.transform = 'translateY(0)';
                        navbar.style.transition = 'transform 0.3s ease-in-out';
                    }
                } else {
                    // At top - always show
                    navbar.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }, false);
        </script>
        
    </body>
</html>
