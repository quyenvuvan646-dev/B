<!doctype html>
<html lang="en">
    <head>
        <title>Shipper Dashboard</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm navbar-dark bg-success">
                <div class="container">
                    <a class="navbar-brand" href="#">Shipper</a>
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <?php
                        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
                        require_once __DIR__ . '/../app/Auth.php';
                        $currentUser = \Auth::user();
                        $isShipper = \Auth::hasRole('shipper');
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
                                $isShipper = true;
                            }
                        }
                    ?>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                            <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL;?>/Home">Trang chính</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL;?>/Home/orderHistory">Lịch sử đơn hàng</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL;?>/Home/order">Giỏ hàng</a></li>
                            <?php if ($isShipper): ?>
                                <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL;?>/Shipper">Phân công giao hàng</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL;?>/Shipper/address">Địa chỉ của bạn</a></li>
                            <?php endif; ?>
                        </ul>
                        <?php if ($currentUser): ?>
                            <div class="d-flex align-items-center me-3 text-white">
                                <small class="me-2">Xin chào, <strong><?php echo htmlspecialchars($currentUser['fullname'] ?? $currentUser['email']); ?></strong></small>
                                <?php if (!empty($roleNames)): foreach ($roleNames as $rn): ?>
                                    <span class="badge bg-light text-dark ms-1"><?php echo htmlspecialchars($rn); ?></span>
                                <?php endforeach; endif; ?>
                            </div>
                            <a href="<?php echo APP_URL; ?>/AuthController/logout" class="btn btn-outline-light ms-2">Đăng xuất</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <?php require_once "./views/Font_end/".$data["page"].".php"; ?>
        </main>
    </body>
</html>
