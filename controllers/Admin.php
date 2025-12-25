<?php
class Admin extends Controller{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
    }
    function show(){
        $obj=$this->model("AdProductTypeModel");
        $data=$obj->all("tblloaisp");
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }
    public function userViewadmin(){
         $this->view("adminPage",["page"=>"usersView"]);
    }

    // 📊 Trang thống kê dashboard mới
    public function statistics()
    {
        $filterType = $_GET['filterType'] ?? 'all';
        $filterValue = null;

        // Normalise filter value based on type
        switch ($filterType) {
            case 'day':
                $filterValue = $_GET['day'] ?? date('Y-m-d');
                break;
            case 'month':
                $filterValue = $_GET['month'] ?? date('Y-m');
                break;
            case 'year':
                $filterValue = $_GET['year'] ?? date('Y');
                break;
            default:
                $filterType = 'all';
                $filterValue = null;
        }

        $adminStatsModel = $this->model('AdminStatisticsModel');
        
        $totalRevenue = $adminStatsModel->getTotalRevenue($filterType, $filterValue);
        $orderStats = $adminStatsModel->getOrderStats($filterType, $filterValue);
        $userStats = $adminStatsModel->getUserStats();
        $productStats = $adminStatsModel->getProductStats();
        $financials = $adminStatsModel->getFinancialSummary($filterType, $filterValue);
        $distributorTaxes = $adminStatsModel->getDistributorTaxes($filterType, $filterValue);
        $topDistributors = $adminStatsModel->getTopDistributors($filterType, $filterValue);
        $bestSellingProducts = $adminStatsModel->getBestSellingProducts(10, $filterType, $filterValue);
        $revenueByType = $adminStatsModel->getRevenueByProductType($filterType, $filterValue);
        $paymentStats = $adminStatsModel->getPaymentStats($filterType, $filterValue);
        $dailyRevenue = $adminStatsModel->getDailyRevenue(30);
        $totalTax = array_sum(array_map(fn($r) => $r['tax'] ?? 0, $distributorTaxes));
        // 🎯 4 thống kê mới
        $outOfStockOrders = $adminStatsModel->getOutOfStockOrders();
        $favoriteProducts = $adminStatsModel->getFavoriteProducts(10);
        $highlyRatedProducts = $adminStatsModel->getHighlyRatedProducts(10);
        // 🎯 Thống kê bổ sung cho admin dashboard
        $statsByType = $adminStatsModel->getStatsByProductType($filterType, $filterValue);
        $statsByProduct = $adminStatsModel->getStatsByProduct($filterType, $filterValue);
        $statsByDistributor = $adminStatsModel->getStatsByDistributor($filterType, $filterValue);
        $statsByBuyer = $adminStatsModel->getStatsByBuyer($filterType, $filterValue);

        $this->view("adminPage", [
            "page" => "AdminStatisticsView",
            "totalRevenue" => $totalRevenue,
            "orderStats" => $orderStats,
            "userStats" => $userStats,
            "productStats" => $productStats,
            "topDistributors" => $topDistributors,
            "bestSellingProducts" => $bestSellingProducts,
            "revenueByType" => $revenueByType,
            "paymentStats" => $paymentStats,
            "dailyRevenue" => $dailyRevenue,
            "financials" => $financials,
            "distributorTaxes" => $distributorTaxes,
            "totalTax" => $totalTax,
            "outOfStockOrders" => $outOfStockOrders,
            "favoriteProducts" => $favoriteProducts,
            "highlyRatedProducts" => $highlyRatedProducts,
            "statsByType" => $statsByType,
            "statsByProduct" => $statsByProduct,
            "statsByDistributor" => $statsByDistributor,
            "statsByBuyer" => $statsByBuyer,
            "filterType" => $filterType,
            "filterValue" => $filterValue
        ]);
    }

    // 📈 Doanh thu theo ngày
    public function revenueByDay($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $statsModel = $this->model('StatisticsModel');
        $revenue = $statsModel->getRevenueByDay($date);
        
        header('Content-Type: application/json');
        echo json_encode($revenue);
        exit();
    }

    // 📈 Doanh thu theo tháng
    public function revenueByMonth($year = null, $month = null)
    {
        if ($year === null) $year = date('Y');
        if ($month === null) $month = date('n');
        
        $statsModel = $this->model('StatisticsModel');
        $revenue = $statsModel->getRevenueByMonth($year, $month);
        
        header('Content-Type: application/json');
        echo json_encode($revenue);
        exit();
    }

    // 📈 Doanh thu theo năm
    public function revenueByYear($year = null)
    {
        if ($year === null) $year = date('Y');
        
        $statsModel = $this->model('StatisticsModel');
        $revenue = $statsModel->getRevenueByYear($year);
        
        header('Content-Type: application/json');
        echo json_encode($revenue);
        exit();
    }

    // 📊 Doanh thu từng sản phẩm
    public function revenueByProduct()
    {
        $statsModel = $this->model('StatisticsModel');
        $products = $statsModel->getRevenueByProduct();
        
        header('Content-Type: application/json');
        echo json_encode($products);
        exit();
    }

    // 📊 Doanh thu từng danh mục
    public function revenueByCategory()
    {
        $statsModel = $this->model('StatisticsModel');
        $categories = $statsModel->getRevenueByCategory();
        
        header('Content-Type: application/json');
        echo json_encode($categories);
        exit();
    }

    // 📦 Tồn kho sản phẩm
    public function inventory()
    {
        $statsModel = $this->model('StatisticsModel');
        $inventory = $statsModel->getInventory();
        
        header('Content-Type: application/json');
        echo json_encode($inventory);
        exit();
    }

    // ⚠️ Sản phẩm sắp hết hàng
    public function lowStock()
    {
        $statsModel = $this->model('StatisticsModel');
        $lowStock = $statsModel->getLowStockProducts();
        
        header('Content-Type: application/json');
        echo json_encode($lowStock);
        exit();
    }

    // 📈 Tăng trưởng doanh thu
    public function revenueGrowth($year = null, $month = null)
    {
        if ($year === null) $year = date('Y');
        if ($month === null) $month = date('n');
        
        $statsModel = $this->model('StatisticsModel');
        $growth = $statsModel->getRevenueGrowth($year, $month);
        
        header('Content-Type: application/json');
        echo json_encode($growth);
        exit();
    }

    // 📉 Biểu đồ doanh thu
    public function chartData($period = 'month', $year = null)
    {
        if ($year === null) $year = date('Y');
        
        $statsModel = $this->model('StatisticsModel');
        $data = $statsModel->getRevenueChart($period, $year);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // Admin comment management endpoints
    public function comments()
    {
        $cm = $this->model('CommentModel');
        $bwm = $this->model('BannedWordModel');
        $comments = $cm->listAllComments();
        $bannedWords = $bwm->all();
        $this->view('adminPage', [
            'page' => 'CommentsView',
            'comments' => $comments,
            'bannedWords' => $bannedWords
        ]);
    }

    // Admin: thêm từ cấm
    public function addBannedWord()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $word = trim($_POST['word'] ?? '');
            if ($word !== '') {
                $this->model('BannedWordModel')->add($word);
            }
        }
        header('Location: ' . APP_URL . '/Admin/comments');
        exit();
    }

    // Admin: xóa từ cấm
    public function deleteBannedWord($word)
    {
        $this->model('BannedWordModel')->remove(urldecode($word));
        header('Location: ' . APP_URL . '/Admin/comments');
        exit();
    }

    public function setCommentVisible($id, $visible)
    {
        $cm = $this->model('CommentModel');
        $cm->setVisibility($id, $visible == '1');
        header('Location: ' . APP_URL . '/Admin/comments');
        exit();
    }

    public function deleteComment($id)
    {
        $cm = $this->model('CommentModel');
        $cm->deleteComment($id);
        header('Location: ' . APP_URL . '/Admin/comments');
        exit();
    }

    // --------- User management ---------
    public function users()
    {
        $this->view('adminPage', ['page' => 'UsersView']);
    }

    // 🏪 Danh sách shop (distributor)
    public function shops()
    {
        // Sử dụng DB class để thực hiện truy vấn
        require_once __DIR__ . '/../app/DB.php';
        $db = new DB();
        
        // Query đơn giản hơn để lấy danh sách distributor
        $sql = "SELECT 
                    u.user_id,
                    u.email,
                    u.fullname as shop_name,
                    u.phone,
                    u.address,
                    u.created_at,
                    u.is_locked
                FROM tbluser u
                WHERE u.user_role = 4
                ORDER BY u.fullname ASC";
        
        $stmt = $db->getDB()->prepare($sql);
        $stmt->execute();
        $shops = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thêm thông tin sản phẩm và rating cho từng shop
        if (!empty($shops)) {
            foreach ($shops as &$shop) {
                $email = $shop['email'];
                
                // Lấy số sản phẩm
                $sqlProducts = "SELECT COUNT(*) as count FROM tblsanpham WHERE email = :email";
                $stmtProducts = $db->getDB()->prepare($sqlProducts);
                $stmtProducts->bindParam(':email', $email);
                $stmtProducts->execute();
                $products = $stmtProducts->fetch();
                $shop['product_count'] = $products['count'] ?? 0;
                
                // Lấy rating trung bình
                $sqlRating = "SELECT ROUND(AVG(c.rating), 2) as avg_rating 
                             FROM comments c
                             JOIN tblsanpham p ON c.masp = p.masp
                             WHERE p.email = :email AND c.rating IS NOT NULL";
                $stmtRating = $db->getDB()->prepare($sqlRating);
                $stmtRating->bindParam(':email', $email);
                $stmtRating->execute();
                $rating = $stmtRating->fetch();
                $shop['avg_rating'] = $rating['avg_rating'] ?? 0;
                
                // Lấy số đơn hàng đã hoàn thành
                $sqlOrders = "SELECT COUNT(DISTINCT o.id) as count 
                             FROM orders o
                             JOIN order_details od ON o.id = od.order_id
                             JOIN tblsanpham p ON od.product_id = p.masp
                             WHERE p.email = :email AND o.delivery_status = 'da_nhan_hang'";
                $stmtOrders = $db->getDB()->prepare($sqlOrders);
                $stmtOrders->bindParam(':email', $email);
                $stmtOrders->execute();
                $orders = $stmtOrders->fetch();
                $shop['order_count'] = $orders['count'] ?? 0;
                
                // Lấy doanh thu
                $sqlRevenue = "SELECT COALESCE(SUM(o.total_amount), 0) as revenue
                              FROM orders o
                              JOIN order_details od ON o.id = od.order_id
                              JOIN tblsanpham p ON od.product_id = p.masp
                              WHERE p.email = :email AND o.delivery_status = 'da_nhan_hang'";
                $stmtRevenue = $db->getDB()->prepare($sqlRevenue);
                $stmtRevenue->bindParam(':email', $email);
                $stmtRevenue->execute();
                $revenue = $stmtRevenue->fetch();
                $shop['total_revenue'] = $revenue['revenue'] ?? 0;
            }
        }
        
        $this->view('adminPage', ['page' => 'AdminShopsView', 'shops' => $shops]);
    }

    public function lockUser($email, $lock)
    {
        $um = $this->model('UserModel');
        $um->setLock(urldecode($email), $lock == '1');
        header('Location: ' . APP_URL . '/Admin/users');
        exit();
    }

    public function deleteUser($email)
    {
        $um = $this->model('UserModel');
        $um->deleteUser(urldecode($email));
        header('Location: ' . APP_URL . '/Admin/users');
        exit();
    }

    // Admin: show orders that were placed (not canceled) and not yet delivered/returned
    public function orders()
    {
        $orderModel = $this->model('OrderModel');
        // Exclude canceled orders (transaction_info = 'huydon') and exclude already delivered/returned
        $sql = "SELECT id, order_code, total_amount, user_email, receiver, phone, address, created_at, transaction_info, delivery_status, is_paid 
                FROM orders 
                WHERE (transaction_info IS NULL OR transaction_info != 'huydon') 
                  AND (delivery_status IS NULL OR (delivery_status NOT IN ('da_nhan_hang','da_tra_hang'))) 
                ORDER BY created_at DESC";
        $orders = $orderModel->select($sql, []);
        $this->view('adminPage', ['page' => 'OrdersView', 'orders' => $orders]);
    }

    // ✅ Admin: show returned orders
    public function returnedOrders()
    {
        $orderModel = $this->model('OrderModel');
        $returnedOrders = $orderModel->getReturnedOrders();
        $this->view('adminPage', ['page' => 'ReturnedOrdersView', 'returnedOrders' => $returnedOrders]);
    }

    // API: Lấy danh sách role có sẵn
    public function getRoles()
    {
        $rm = $this->model('RoleModel');
        $roles = $rm->allRoles();
        header('Content-Type: application/json');
        echo json_encode($roles);
        exit();
    }

    // API: Lấy roles đã gán cho user theo id hoặc email
    public function getUserRoles($userIdOrEmail)
    {
        $um = $this->model('UserModel');
        $roles = [];
        if (is_numeric($userIdOrEmail)) {
            $roles = $um->getUserRolesById(intval($userIdOrEmail));
        } else {
            // treat as email (URL-encoded)
            $email = urldecode($userIdOrEmail);
            $stmt = $this->model('UserModel')->findByEmail($email);
            $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            if ($user) {
                $uid = $user['id'] ?? $user['user_id'] ?? null;
                if ($uid) $roles = $um->getUserRolesById($uid);
            }
        }

        header('Content-Type: application/json');
        echo json_encode($roles);
        exit();
    }

    // API: Đồng bộ roles cho user (POST) - expects `user_id` and `roles` (array of role ids)
    public function assignRoles()
    {
        $input = $_POST;
        $roles = isset($input['roles']) ? $input['roles'] : [];
        
        // Handle JSON string from FormData
        if (!is_array($roles)) {
            $decoded = json_decode($roles, true);
            $roles = is_array($decoded) ? $decoded : [];
        }

        $um = $this->model('UserModel');

        // resolve user id: prefer user_id, fallback to user_email
        $userId = 0;
        if (isset($input['user_id']) && is_numeric($input['user_id'])) {
            $userId = intval($input['user_id']);
        } elseif (!empty($input['user_email'])) {
            $email = trim($input['user_email']);
            $stmt = $this->model('UserModel')->findByEmail($email);
            $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            if ($user) {
                $userId = $user['id'] ?? $user['user_id'] ?? 0;
            }
        }

        $ok = false;
        if ($userId && is_numeric($userId)) {
            // Accept array or single value — pick first role id or default to 1
            $roleId = 1;
            if (is_array($roles) && count($roles) > 0) {
                $roleId = intval($roles[0]);
            } elseif (is_numeric($roles)) {
                $roleId = intval($roles);
            }
            $ok = $um->assignRole(intval($userId), $roleId);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
        exit();
    }

     public function updateUserRole() {
        $user_id = $_POST['user_id'] ?? null;
        $role = $_POST['role'] ?? null;

        if (!$user_id || !$role) {
            echo json_encode(["success" => false]);
            return;
        }

        $userModel = $this->model("UserModel");
        $ok = $userModel->updateUserRole($user_id, $role);

        echo json_encode(["success" => $ok]);
    }

    // ============ VOUCHER MANAGEMENT ============

    // Trang quản lý voucher admin
    public function vouchers()
    {
        $this->view("adminPage", ["page" => "AdminVouchersView"]);
    }

    // API: Lấy tất cả vouchers
    public function getAllVouchers()
    {
        header('Content-Type: application/json');
        $voucherModel = $this->model("Advoucher");
        $vouchers = $voucherModel->getAll();
        
        echo json_encode([
            'success' => true,
            'vouchers' => $vouchers
        ]);
        exit();
    }

    // API: Lấy vouchers theo loại
    public function getVouchersByType()
    {
        header('Content-Type: application/json');
        $type = $_GET['type'] ?? 'goc';
        $voucherModel = $this->model("Advoucher");
        
        if ($type === 'ship') {
            $vouchers = $voucherModel->getShipVouchers();
        } else {
            $vouchers = $voucherModel->getGocVouchers();
        }
        
        echo json_encode([
            'success' => true,
            'vouchers' => $vouchers
        ]);
        exit();
    }

    // API: Lấy 1 voucher theo ID
    public function getVoucherById()
    {
        header('Content-Type: application/json');
        $vc_id = $_GET['vc_id'] ?? null;
        
        if (!$vc_id) {
            echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ']);
            exit();
        }
        
        $voucherModel = $this->model("Advoucher");
        $voucher = $voucherModel->getById($vc_id);
        
        echo json_encode([
            'success' => !!$voucher,
            'voucher' => $voucher
        ]);
        exit();
    }

    // Thêm voucher mới
    public function addVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/?url=Admin/vouchers');
            exit();
        }

        $vc_id = $_POST['vc_id'] ?? null;
        $giagiam = $_POST['giagiam'] ?? 0;
        $giatoithieu = $_POST['giatoithieu'] ?? 0;
        $soluong = $_POST['soluong'] ?? 0;
        $ngaybatdau = $_POST['ngaybatdau'] ?? date('Y-m-d');
        $ngayketthuc = $_POST['ngayketthuc'] ?? date('Y-m-d', strtotime('+1 month'));
        $trangthai = isset($_POST['trangthai']) ? 1 : 0;
        $chuc_nang = $_POST['chuc_nang'] ?? 'goc';

        if (!$vc_id) {
            echo '<div class="alert alert-danger">Mã voucher không được để trống</div>';
            $this->view("adminPage", ["page" => "AdminVouchersView"]);
            return;
        }

        $voucherModel = $this->model("Advoucher");
        $result = $voucherModel->insert($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang);

        if ($result) {
            echo '<div class="alert alert-success">✓ Thêm voucher thành công</div>';
        } else {
            echo '<div class="alert alert-danger">✗ Thêm voucher thất bại</div>';
        }

        $this->view("adminPage", ["page" => "AdminVouchersView"]);
    }

    // Cập nhật voucher
    public function updateVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit();
        }

        header('Content-Type: application/json');

        $vc_id = $_POST['vc_id'] ?? null;
        $giagiam = $_POST['giagiam'] ?? 0;
        $giatoithieu = $_POST['giatoithieu'] ?? 0;
        $soluong = $_POST['soluong'] ?? 0;
        $ngaybatdau = $_POST['ngaybatdau'] ?? date('Y-m-d');
        $ngayketthuc = $_POST['ngayketthuc'] ?? date('Y-m-d');
        $trangthai = isset($_POST['trangthai']) ? 1 : 0;
        $chuc_nang_req = $_POST['chuc_nang'] ?? null;

        if (!$vc_id) {
            echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ']);
            exit();
        }

        $voucherModel = $this->model("Advoucher");
        // Lấy chuc_nang hiện tại nếu không gửi từ form
        $current = $voucherModel->getById($vc_id);
        $chuc_nang = $chuc_nang_req ?: ($current['chuc_nang'] ?? 'goc');

        $result = $voucherModel->update($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai, $chuc_nang);

        echo json_encode(['success' => $result]);
        exit();
    }

    // Xóa voucher
    public function deleteVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit();
        }

        header('Content-Type: application/json');

        $vc_id = $_POST['vc_id'] ?? null;

        if (!$vc_id) {
            echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ']);
            exit();
        }

        $voucherModel = $this->model("Advoucher");
        $result = $voucherModel->deleteVoucher($vc_id);

        echo json_encode(['success' => $result]);
        exit();
    }

    // ========== DISTRIBUTOR CONTRACT MANAGEMENT ==========
    
    // Display all contracts for admin review
    public function contractsManagement()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        
        // Verify admin role
        Auth::requireRole('admin');
        
        $hopDongModel = $this->model('HopDongModel');
        
        // Get all pending contracts
        $pendingContracts = $hopDongModel->getPendingContracts();
        
        // Get approved and rejected contracts for history
        $approvedContracts = $hopDongModel->getContractsByStatus('approved');
        $rejectedContracts = $hopDongModel->getContractsByStatus('rejected');
        
        $this->view('adminPage', [
            'page' => 'AdminContractsView',
            'pendingContracts' => $pendingContracts,
            'approvedContracts' => $approvedContracts,
            'rejectedContracts' => $rejectedContracts
        ]);
    }
    
    // View contract details
    public function viewContract($id)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        
        Auth::requireRole('admin');
        
        $hopDongModel = $this->model('HopDongModel');
        $contract = $hopDongModel->getContractById($id);
        
        if (!$contract) {
            $_SESSION['error'] = 'Hợp đồng không tồn tại';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
            exit();
        }
        
        // Get user info if needed
        $userModel = $this->model('UserModel');
        $userStmt = $userModel->findByEmail($contract['user_email']);
        $user = $userStmt ? $userStmt->fetch(PDO::FETCH_ASSOC) : null;
        
        $this->view('adminPage', [
            'page' => 'AdminContractDetailView',
            'contract' => $contract,
            'user' => $user
        ]);
    }
    
    // Approve contract and upgrade user to distributor
    public function approveContract($id)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        
        Auth::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
            exit();
        }
        
        $adminNotes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : '';
        
        try {
            $hopDongModel = $this->model('HopDongModel');
            $contract = $hopDongModel->getContractById($id);
            
            if (!$contract) {
                $_SESSION['error'] = 'Hợp đồng không tồn tại';
                header('Location: ' . APP_URL . '/Admin/contractsManagement');
                exit();
            }
            
            // Update contract status
            $hopDongModel->updateContractStatus($contract['user_email'], 'approved', $adminNotes);
            
            // Upgrade user to distributor role
            $userModel = $this->model('UserModel');
            $userStmt = $userModel->findByEmail($contract['user_email']);
            $user = $userStmt ? $userStmt->fetch(PDO::FETCH_ASSOC) : null;
            
            if ($user && $user['user_id']) {
                // Role ID 4 = distributor
                $userModel->assignRole($user['user_id'], 4);
            }
            
            $_SESSION['success'] = 'Đã duyệt hợp đồng và nâng cấp người dùng thành nhà phân phối';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        }
        exit();
    }
    
    // Reject contract
    public function rejectContract($id)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        
        Auth::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
            exit();
        }
        
        $adminNotes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : '';
        
        if (!$adminNotes) {
            $_SESSION['error'] = 'Vui lòng nhập lý do từ chối';
            header('Location: ' . APP_URL . '/Admin/viewContract/' . $id);
            exit();
        }
        
        try {
            $hopDongModel = $this->model('HopDongModel');
            $contract = $hopDongModel->getContractById($id);
            
            if (!$contract) {
                $_SESSION['error'] = 'Hợp đồng không tồn tại';
                header('Location: ' . APP_URL . '/Admin/contractsManagement');
                exit();
            }
            
            // Update contract status
            $hopDongModel->updateContractStatus($contract['user_email'], 'rejected', $adminNotes);
            
            $_SESSION['success'] = 'Đã từ chối hợp đồng';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        }
        exit();
    }

    // Delete contract
    public function deleteContract($id) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        Auth::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
            exit();
        }
        
        try {
            $hopDongModel = $this->model('HopDongModel');
            $contract = $hopDongModel->getContractById($id);
            
            if (!$contract) {
                $_SESSION['error'] = 'Hợp đồng không tồn tại';
                header('Location: ' . APP_URL . '/Admin/contractsManagement');
                exit();
            }
            
            // Get user email from contract
            $userEmail = $contract['user_email'];
            
            // Delete from database
            $sql = "DELETE FROM hop_dong WHERE id = ?";
            $hopDongModel->query($sql, [$id]);
            
            // Downgrade user role from distributor (4) back to buyer (1)
            $userModel = $this->model('UserModel');
            $updateQuery = "UPDATE tbluser SET user_role = 1 WHERE email = :email";
            $stmt = $userModel->getDB()->prepare($updateQuery);
            $stmt->bindParam(":email", $userEmail);
            $stmt->execute();
            
            $_SESSION['success'] = 'Đã xóa hợp đồng thành công. Người dùng đã được trở lại trạng thái Người Mua.';
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa hợp đồng: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin/contractsManagement');
        }
        exit();
    }

    // View notifications
    public function notifications() {
        $notificationModel = $this->model('NotificationModel');
        $unreadCount = $notificationModel->countUnread();
        $notifications = $notificationModel->getAllNotifications(20, 0);
        
        $this->view('adminPage', [
            'page' => 'AdminNotificationsView',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    // ==================== BANNER MANAGEMENT ====================
    
    // View all banners
    public function banners() {
        try {
            $bannerModel = $this->model('BannerModel');
            $banners = $bannerModel->getAllBanners();
            
            $this->view('adminPage', [
                'page' => 'AdminBannersView',
                'banners' => $banners
            ]);
        } catch (Exception $e) {
            error_log("Error in Admin::banners(): " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi tải trang: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin');
            exit();
        }
    }

    // Create new banner
    public function createBanner() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/banners');
            exit();
        }

        try {
            $title = $_POST['title'] ?? '';
            $linkUrl = $_POST['link_url'] ?? '';
            $displayOrder = (int)($_POST['display_order'] ?? 0);
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            // Handle file upload
            $imagePath = '';
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/images/banners/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $extension = pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION);
                $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $targetPath)) {
                    $imagePath = 'banners/' . $filename;
                } else {
                    throw new Exception('Lỗi khi upload ảnh');
                }
            } else {
                throw new Exception('Vui lòng chọn ảnh banner');
            }

            $bannerModel = $this->model('BannerModel');
            $bannerModel->createBanner($title, $imagePath, $linkUrl, $displayOrder, $isActive);

            $_SESSION['success'] = 'Thêm banner thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Admin/banners');
        exit();
    }

    // Show edit banner page
    public function editBanner($id) {
        try {
            $bannerModel = $this->model('BannerModel');
            $banner = $bannerModel->getBannerById($id);
            
            if (!$banner) {
                $_SESSION['error'] = 'Banner không tồn tại';
                header('Location: ' . APP_URL . '/Admin/banners');
                exit();
            }
            
            $this->view('adminPage', [
                'page' => 'AdminBannerEditView',
                'banner' => $banner
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin/banners');
            exit();
        }
    }

    // Update banner
    public function updateBanner($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/banners');
            exit();
        }

        try {
            $bannerModel = $this->model('BannerModel');
            $banner = $bannerModel->getBannerById($id);

            if (!$banner) {
                throw new Exception('Banner không tồn tại');
            }

            $title = $_POST['title'] ?? $banner['title'];
            $linkUrl = $_POST['link_url'] ?? $banner['link_url'];
            $displayOrder = isset($_POST['display_order']) ? (int)$_POST['display_order'] : $banner['display_order'];
            $isActive = isset($_POST['is_active']) ? 1 : ($banner['is_active'] ?? 1);
            $imagePath = $banner['image_path'];

            // Handle new file upload if provided
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/images/banners/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $extension = pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION);
                $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $targetPath)) {
                    // Delete old image
                    $oldImagePath = __DIR__ . '/../public/images/' . $banner['image_path'];
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                    $imagePath = 'banners/' . $filename;
                }
            }

            $bannerModel->updateBanner($id, $title, $imagePath, $linkUrl, $displayOrder, $isActive);

            $_SESSION['success'] = 'Cập nhật banner thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Admin/banners');
        exit();
    }

    // Delete banner
    public function deleteBanner($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            header('Location: ' . APP_URL . '/Admin/banners');
            exit();
        }

        try {
            $bannerModel = $this->model('BannerModel');
            $banner = $bannerModel->getBannerById($id);

            if (!$banner) {
                throw new Exception('Banner không tồn tại');
            }

            // Delete image file
            $imagePath = __DIR__ . '/../public/images/' . $banner['image_path'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }

            $bannerModel->deleteBanner($id);

            $_SESSION['success'] = 'Xóa banner thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Admin/banners');
        exit();
    }

    // Toggle banner active status
    public function toggleBanner($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/banners');
            exit();
        }

        try {
            $bannerModel = $this->model('BannerModel');
            $bannerModel->toggleActive($id);

            $_SESSION['success'] = 'Cập nhật trạng thái banner thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Admin/banners');
        exit();
    }
    
    // Get unread notifications count (for AJAX)
    public function getUnreadCount() {
        header('Content-Type: application/json');
        
        $notificationModel = $this->model('NotificationModel');
        $count = $notificationModel->countUnread();
        
        echo json_encode([
            'count' => $count,
            'status' => 'success'
        ]);
        exit();
    }
    
    // Mark notification as read
    public function markNotificationRead($notificationId) {
        header('Content-Type: application/json');
        
        $notificationModel = $this->model('NotificationModel');
        $result = $notificationModel->markAsRead($notificationId);
        
        echo json_encode([
            'success' => $result > 0,
            'message' => $result > 0 ? 'Marked as read' : 'Failed to mark as read'
        ]);
        exit();
    }
    
    // Mark all notifications as read
    public function markAllNotificationsRead() {
        header('Content-Type: application/json');
        
        $notificationModel = $this->model('NotificationModel');
        $result = $notificationModel->markAllAsRead();
        
        echo json_encode([
            'success' => true,
            'message' => 'All notifications marked as read',
            'count' => $result
        ]);
        exit();
    }
    
    // View support ticket detail
    public function viewSupportTicket($ticketId) {
        $supportModel = $this->model('SupportTicketModel');
        $ticket = $supportModel->getTicketById($ticketId);
        
        if (!$ticket) {
            $_SESSION['error'] = 'Ticket không tồn tại';
            header('Location: ' . APP_URL . '/Admin/notifications');
            exit();
        }
        
        // Mark notification as read
        $notificationModel = $this->model('NotificationModel');
        $notificationModel->markAsRead($ticketId);
        
        $this->view('adminPage', [
            'page' => 'AdminSupportTicketView',
            'ticket' => $ticket
        ]);
    }
    
    // Admin reply to support ticket
    public function replyToTicket() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/notifications');
            exit();
        }
        
        $ticketId = $_POST['ticket_id'] ?? null;
        $response = $_POST['admin_response'] ?? '';
        
        if (!$ticketId || !$response) {
            $_SESSION['error'] = 'Vui lòng nhập nội dung phản hồi';
            header('Location: ' . APP_URL . '/Admin/viewSupportTicket/' . $ticketId);
            exit();
        }
        
        try {
            $supportModel = $this->model('SupportTicketModel');
            $ticket = $supportModel->getTicketById($ticketId);
            
            if (!$ticket) {
                $_SESSION['error'] = 'Ticket không tồn tại';
                header('Location: ' . APP_URL . '/Admin/notifications');
                exit();
            }
            
            // Update ticket with admin response
            $supportModel->addAdminResponse($ticketId, $response);
            
            // Create notification for user
            $notificationModel = $this->model('NotificationModel');
            $notificationModel->createNotification(
                'support_response',
                'Admin đã trả lời yêu cầu hỗ trợ của bạn',
                'Phản hồi: ' . substr($response, 0, 100) . '...',
                $ticketId,
                'support_ticket'
            );
            
            $_SESSION['success'] = 'Đã gửi phản hồi thành công';
            header('Location: ' . APP_URL . '/Admin/notifications');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Admin/viewSupportTicket/' . $ticketId);
        }
        exit();
    }

    // Show support chat interface
    public function supportChat() {
        $this->view('adminPage', [
            'page' => 'AdminSupportChatView'
        ]);
    }
    
    // Get list of users with support messages
    public function getSupportUserList() {
        header('Content-Type: application/json');
        
        try {
            $supportModel = $this->model('SupportTicketModel');
            
            // Get all tickets grouped by user email
            $sql = "SELECT 
                        user_email,
                        COUNT(*) as message_count,
                        MAX(created_at) as last_time,
                        (SELECT message FROM support_tickets WHERE user_email = st.user_email ORDER BY created_at DESC LIMIT 1) as last_message,
                        SUM(CASE WHEN admin_response IS NULL THEN 1 ELSE 0 END) as unread_count
                    FROM support_tickets st
                    GROUP BY user_email
                    ORDER BY last_time DESC";
            
            $users = $supportModel->select($sql, []);
            
            // Format data
            $formattedUsers = array_map(function($user) {
                return [
                    'email' => $user['user_email'],
                    'message_count' => (int)$user['message_count'],
                    'last_time' => date('d/m H:i', strtotime($user['last_time'])),
                    'last_message' => substr($user['last_message'], 0, 50) . (strlen($user['last_message']) > 50 ? '...' : ''),
                    'unread_count' => (int)$user['unread_count']
                ];
            }, $users);
            
            echo json_encode(['success' => true, 'users' => $formattedUsers]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }
    
    // Get chat history with specific user
    public function getChatWithUser() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request']);
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            echo json_encode(['error' => 'Email không hợp lệ']);
            exit();
        }
        
        try {
            $supportModel = $this->model('SupportTicketModel');
            
            // Get tickets ordered by time (oldest first, newest last)
            $sql = "SELECT * FROM support_tickets 
                    WHERE user_email = ? 
                    ORDER BY created_at ASC";
            $tickets = $supportModel->select($sql, [$email]);
            
            $messages = [];
            foreach ($tickets as $ticket) {
                // User message
                $messages[] = [
                    'type' => 'user',
                    'message' => nl2br(htmlspecialchars($ticket['message'])),
                    'timestamp' => date('d/m H:i', strtotime($ticket['created_at']))
                ];
                
                // Admin response (if exists)
                if (!empty($ticket['admin_response'])) {
                    $messages[] = [
                        'type' => 'admin',
                        'message' => nl2br(htmlspecialchars($ticket['admin_response'])),
                        'timestamp' => date('d/m H:i', strtotime($ticket['updated_at']))
                    ];
                }
            }
            
            echo json_encode(['success' => true, 'messages' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }
    
    // Send support response
    public function sendSupportResponse() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request']);
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        $response = $_POST['response'] ?? '';
        
        if (empty($email) || empty($response)) {
            echo json_encode(['error' => 'Thiếu thông tin']);
            exit();
        }
        
        try {
            $supportModel = $this->model('SupportTicketModel');
            
            // Create new ticket with admin response
            $ticketId = $supportModel->createTicket($email, 'Phản hồi từ admin', 'closed');
            
            if ($ticketId) {
                // Update with admin response
                $supportModel->addAdminResponse($ticketId, $response);
                
                // Create notification for user
                try {
                    $notificationModel = $this->model('NotificationModel');
                    $notificationModel->createNotification(
                        'support_response',
                        'Admin đã phản hồi yêu cầu hỗ trợ',
                        substr($response, 0, 100) . (strlen($response) > 100 ? '...' : ''),
                        $ticketId,
                        'support_ticket'
                    );
                } catch (Exception $e) {
                    error_log('Notification creation failed: ' . $e->getMessage());
                }
                
                echo json_encode(['success' => true, 'ticketId' => $ticketId]);
            } else {
                echo json_encode(['error' => 'Không thể tạo phản hồi']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    // 👤 Xem thông tin chi tiết khách hàng
    public function customerDetail($email)
    {
        $email = urldecode($email);
        
        require_once __DIR__ . '/../app/DB.php';
        $db = new DB();
        
        // Get customer basic info
        $sqlCustomer = "SELECT user_id, email, fullname, phone, address, created_at, is_locked, is_verified 
                        FROM tbluser 
                        WHERE email = :email AND (user_role = 1 OR user_role = 2)
                        LIMIT 1";
        $stmt = $db->getDB()->prepare($sqlCustomer);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$customer) {
            header('Location: ' . APP_URL . '/Admin/users');
            exit();
        }
        
        // Get customer order stats
        $sqlStats = "SELECT COUNT(*) as total_orders, SUM(total_amount) as total_spent 
                     FROM orders 
                     WHERE user_email = :email";
        $stmt = $db->getDB()->prepare($sqlStats);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC) ?? ['total_orders' => 0, 'total_spent' => 0];
        
        // Get recent orders
        $sqlOrders = "SELECT id, created_at, total_amount, delivery_status 
                      FROM orders 
                      WHERE user_email = :email 
                      ORDER BY created_at DESC 
                      LIMIT 10";
        $stmt = $db->getDB()->prepare($sqlOrders);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('adminPage', [
            'page' => 'AdminCustomerDetailView',
            'customer' => $customer,
            'stats' => $stats,
            'orders' => $orders
        ]);
    }
    
    // 🏪 Xem thông tin chi tiết shop
    public function shopDetail($email)
    {
        $email = urldecode($email);
        
        require_once __DIR__ . '/../app/DB.php';
        $db = new DB();
        
        // Get shop basic info
        $sqlShop = "SELECT user_id, email, fullname as shop_name, phone, address, created_at, is_locked 
                    FROM tbluser 
                    WHERE email = :email AND user_role = 4
                    LIMIT 1";
        $stmt = $db->getDB()->prepare($sqlShop);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $shop = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$shop) {
            header('Location: ' . APP_URL . '/Admin/shops');
            exit();
        }
        
        // Get shop product count
        $sqlProducts = "SELECT COUNT(*) as product_count FROM tblsanpham WHERE email = :email";
        $stmt = $db->getDB()->prepare($sqlProducts);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get shop rating
        $sqlRating = "SELECT AVG(c.rating) as avg_rating, COUNT(c.id) as review_count 
                      FROM comments c 
                      JOIN tblsanpham p ON c.masp = p.masp 
                      WHERE p.email = :email AND c.rating IS NOT NULL";
        $stmt = $db->getDB()->prepare($sqlRating);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $ratingData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get shop order and revenue stats
        $sqlStats = "SELECT COUNT(DISTINCT o.id) as total_orders, 
                            SUM(CASE WHEN o.delivery_status = 'da_nhan_hang' THEN od.total ELSE 0 END) as total_revenue 
                     FROM orders o 
                     JOIN order_details od ON o.id = od.order_id 
                     JOIN tblsanpham p ON od.product_id = p.masp 
                     WHERE p.email = :email";
        $stmt = $db->getDB()->prepare($sqlStats);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get top products
        $sqlTopProducts = "SELECT p.masp, p.tensp, COUNT(od.id) as sold_count, SUM(od.total) as revenue 
                           FROM order_details od 
                           JOIN tblsanpham p ON od.product_id = p.masp 
                           WHERE p.email = :email 
                           GROUP BY p.masp 
                           ORDER BY sold_count DESC 
                           LIMIT 5";
        $stmt = $db->getDB()->prepare($sqlTopProducts);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('adminPage', [
            'page' => 'AdminShopDetailView',
            'shop' => $shop,
            'product_count' => $productData['product_count'] ?? 0,
            'avg_rating' => round($ratingData['avg_rating'] ?? 0, 1),
            'review_count' => $ratingData['review_count'] ?? 0,
            'stats' => $stats,
            'topProducts' => $topProducts
        ]);
    }

    // 🏢 Xem thông tin chi tiết distributor (Alias cho shopDetail)
    public function distributorDetail($email)
    {
        // Distributor là một loại shop (role 4), nên gọi shopDetail
        $this->shopDetail($email);
    }

}